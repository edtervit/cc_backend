<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\ImageTopics;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

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
            'skipUnsplashApiCall' => ['boolean', 'required']
        ]);

        $topic = $request->input('topic');
        $colour = $request->input('colour');
        $orientation = $request->input('orientation');
        $skipUnsplashApiCall = $request->input('skipUnsplashApiCall');
        $limit = $request->input('limit');


        $headers = [
            'User-agent' => 'Mozilla/5.0',
            'Content-Length' => '0',
            'Accept' => 'application/json',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Authorization' => 'Client-ID ' . env('UNSPLASH_API_ACCESS_KEY'),
        ];

        // Create a client with a base URI
        $client = new Client(['base_uri' => 'https://api.unsplash.com/']);

        // $response = $client->request('GET', 'topics/animals/photos/?per_page=3&order_by=popular', ['headers' => $headers]);
        $response = $client->request('GET', '/search/photos?per_page=3&order_by=relevant&color=orange&query=%20', ['headers' => $headers]);

        $data = json_decode($response->getBody(), true);
        return $data;
        //first try to to call unsplash api, if no requests left just serve what we have from db, 

        function insertNewData($arrayOfPhotos)
        {
            foreach ($arrayOfPhotos as $image) {

                //check if image already exists in our db: 
                $exists = Image::where('unsplash_id', '=', $image['id'])->first();

                if ($exists) continue;

                $createdImage = Image::firstOrCreate([
                    'url' => $image['urls']['regular'],
                    'description' => $image['alt_description'] ?? $image['description'] ?? null,
                    'orientation' => Image::orientationChecker($image['width'], $image['height'], 10),
                    'artist_name' => $image['user']['name'],
                    'artist_profile_url' => $image['user']['links']['html'],
                    'unsplash_id' => $image['id'],
                ]);

                //assign the topic
                if (count($image['topic_submissions']) > 0) {
                    $topicSlugs = array_keys($image['topic_submissions']);
                    foreach ($topicSlugs as $slug) {
                        //check if we have that slug in db
                        $topicWeHave = ImageTopics::where('slug', '=', $slug)->first();
                        if ($topicWeHave) {
                            DB::table('image_image_topic')->insert([
                                'image_id' => $createdImage->id,
                                'image_topic_id' => $topicWeHave->id
                            ]);
                        }
                    }
                }

                //ccheck for image colour propery and assign

            }
            return 'test';
        }

        //no topic - no colour 
        if (!isset($topic) && !isset($colour) && !$skipUnsplashApiCall) {

            $response = $client->request('GET', 'photos?per_page=20&order_by=popular', ['headers' => $headers]);
            $data = json_decode($response->getBody(), true);
            // insertNewData($data);
            return $data;
            return 'no topic or colour';
        }

        //topic only


        //colour only

        //colour + topic 


        // return $data;
        return 'woops';
    }
}
