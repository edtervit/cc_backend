<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\ColourSchemes;
use Illuminate\Database\Eloquent\Collection;

class ColourSchemesController extends Controller
{
    //get messages, returns general type only as default
    public function getColours(): Collection
    {
        $request = request();
        $request->validate([
            'limit' => 'integer',
            'random_order' => 'boolean'
        ]);

        $limit = $request->input('limit');
        $random_order = $request->input('random_order');

        $colourSchemes = ColourSchemes::query();

        if (filled($random_order) && $random_order) {
            $colourSchemes = $colourSchemes->inRandomOrder();
        }

        if ($limit) {
            $colourSchemes = $colourSchemes->take($limit);
        }

        return $colourSchemes->get();
    }

    public function scrapeColourSchemes()
    {
        $request = request();
        $request->validate([
            'amount' => 'integer',
        ]);

        $amount = $request->amount ?? 500;

        $headers = [
            'User-agent' => 'Mozilla/5.0',
            'Content-Length' => '0',
            'Accept' => 'application/json',
            'Accept-Encoding' => 'gzip, deflate, br',
        ];
        // Create a client with a base URI
        $client = new Client(['base_uri' => 'https://www.colr.org/json/schemes/random/']);

        $response = $client->request('GET', $amount . '?schemes_size_limit=5', ['headers' => $headers]);
        $data = json_decode($response->getBody(), true);

        $chunks = array_chunk($data['schemes'], 100);

        foreach ($chunks as $chunk) {
            foreach ($chunk as  $scheme) {
                $coloursArr = $scheme['colors'];
                if (count($coloursArr) === 5) {
                    ColourSchemes::firstOrCreate([
                        'colour_1' => $coloursArr[0],
                        'colour_2' => $coloursArr[1],
                        'colour_3' => $coloursArr[2],
                        'colour_4' => $coloursArr[3],
                        'colour_5' => $coloursArr[4],
                    ]);
                }
            }
        }
        return ColourSchemes::all();
    }
}
