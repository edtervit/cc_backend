<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\ImageTopics;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response;


class ImageController extends Controller
{
    //
    //get images, returns general type only as default
    public function getImages()
    {

        $request = request();
        $request->validate([
            'topic' => 'exists:image_topics,slug',
            'colour' =>  ['string', 'in:"' . implode('", "', Image::ALL_IMAGE_COLOURS) . '"'],
            'orientation' =>  ['string', 'required', 'in:"' . implode('", "', Image::ALL_IMAGE_ORIENTATIONS) . '"'],
            'limit' => 'integer',
            'skipUnsplashApiCall' => ['boolean', 'required'],
            'mustIncludeTheseImages' => 'array',
            'mustIncludeTheseImages.*' => 'integer',
        ]);

        $topic = $request->input('topic');
        $colour = $request->input('colour');
        $orientation = $request->input('orientation');
        $skipUnsplashApiCall = $request->input('skipUnsplashApiCall');
        $mustIncludeTheseImages = $request->input('mustIncludeTheseImages');
        $limit = $request->input('limit');

        //when calling unsplash api, how many pages shall we go through (max 30 images per page);
        //currently 5 because of limits we have on the trial account.
        $maxAmountOfExtraPageAPICalls = 5;

        $headers = [
            'User-agent' => 'Mozilla/5.0',
            'Content-Length' => '0',
            'Accept' => 'application/json',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Authorization' => 'Client-ID ' . env('UNSPLASH_API_ACCESS_KEY'),
        ];

        // Create a client with a base URI
        $client = new Client(['base_uri' => 'https://api.unsplash.com/']);

        $isSearchEndpoint = false;
        //no topic - no colour
        if (!isset($topic) && !isset($colour)) {
            $url = 'photos?per_page=30&order_by=popular';
        }

        //topic only
        if (isset($topic) && !isset($colour)) {
            $url = 'topics/' . $topic . '/photos/?per_page=30&page=100&order_by=popular&orientation=' . $orientation;
        }

        //colour only
        if (!isset($topic) && isset($colour)) {
            $url = '/search/photos?per_page=30&order_by=relevant&query=' . $colour . '&orientation=' . $orientation;
            $isSearchEndpoint = true;
        }

        //colour + topic
        if (isset($topic) && isset($colour)) {
            $url = '/search/photos?per_page=30&order_by=relevant&color=' . $colour . '&orientation=' . $orientation . '&query=' . $topic;
            $isSearchEndpoint = true;
        }

        //make the request
        if (!$skipUnsplashApiCall && $url) {
            $errorInRequest = false;
            try {
                $response = $client->request('GET', $url, ['headers' => $headers]);
            } catch (\Throwable $th) {
                $errorInRequest = true;
            }

            if (!$errorInRequest) {
                $data = json_decode($response->getBody(), true);

                if (!$isSearchEndpoint) Image::insertArryOfImagesFromUnsplashApi($data);

                if ($isSearchEndpoint) {
                    if (count($data['results']) > 0) Image::insertArryOfImagesFromUnsplashApi($data['results'], $colour);
                }

                //if there is more than one page of results then get all pages.
                if ($response->hasHeader('X-Total') && $response->hasHeader('X-Per-Page')) {
                    $amountOfResults = $response->getHeader('X-Total')[0];
                    $perPage = $response->getHeader('X-Per-Page')[0];

                    //round up
                    $amountOfPages = ceil($amountOfResults / $perPage);
                    if ($amountOfPages > 1) {
                        //if more than one page
                        $requests = function ($total, $headers, $url, $maxAmountOfExtraPageAPICalls) {
                            // run whichever is bigger, total amount of pages or the max amount to call.
                            $totalToRun = $total <= $maxAmountOfExtraPageAPICalls ? $total : $maxAmountOfExtraPageAPICalls;
                            for ($i = 2; $i <= $totalToRun; $i++) {
                                $uri = $url . '&page=' . $i;
                                yield new Psr7Request('GET', $uri, $headers);
                            }
                        };

                        $pool = new Pool($client, $requests($amountOfPages, $headers, $url, $maxAmountOfExtraPageAPICalls), [
                            'concurrency' => 5,
                            'fulfilled' => function (Response $response) use ($colour, $isSearchEndpoint) {
                                //pass
                                $data = json_decode($response->getBody(), true);
                                if ($isSearchEndpoint) {
                                    if (count($data['results']) > 0) Image::insertArryOfImagesFromUnsplashApi($data['results'], $colour);
                                }
                                if (!$isSearchEndpoint) Image::insertArryOfImagesFromUnsplashApi($data);
                            },
                        ]);
                        $promise = $pool->promise();
                        $promise->wait();
                    }
                }
            }
        }

        //orientation
        $images = Image::where('orientation', '=', $orientation);

        //Colour
        if(isset($colour)){
            $images = $images->where('colour', '=', $colour);
        }

        //topic
        if(isset($topic)){
            $imageTopic = ImageTopics::where('slug', '=', $topic)->firstOrFail();
            $imagesWithTopicIds = $imageTopic->images()->get()->pluck('id');
            $images = $images->whereIn('id', $imagesWithTopicIds);
        }

        //limit
        if(isset($limit)){
            $images = $images->inRandomOrder()->take($limit);
        }

        $finalRes = $images->with('imageTopics')->get();

        if(isset($mustIncludeTheseImages)){
            $imagesToAdd = Image::whereIn('id', $mustIncludeTheseImages)->with('imageTopics')->get();
            $finalRes = $imagesToAdd->merge($finalRes);
        }

        return $finalRes;
    }
}
