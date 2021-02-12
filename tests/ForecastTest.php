<?php

use Illuminate\Support\Facades\Http;

class ForecastTest extends TestCase
{
    /** @test */
    public function test_musement_api()
    {
        $response = Http::get(config('app.musement.cities'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEquals(0, count($response->json()));
        $this->assertContains('Amsterdam', $response->json()[0]);
        $this->assertArrayHasKey('name', $response->json()[0]);
        $this->assertArrayHasKey('latitude', $response->json()[0]);
        $this->assertArrayHasKey('longitude', $response->json()[0]);
    }

    /** @test */
    public function test_fake_musement_api()
    {
        Http::fake([
            config('app.musement.cities') => $this->fakeMusementApi()
        ]);

        $response = Http::get(config('app.musement.cities'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEquals(0, count($response->json()));
        $this->assertContains('Fake Amsterdam', $response->json()[0]);
        $this->assertArrayHasKey('name', $response->json()[0]);
        $this->assertArrayHasKey('latitude', $response->json()[0]);
        $this->assertArrayHasKey('longitude', $response->json()[0]);
    }

    /** @test */
    public function test_weather_api_key_is_set()
    {
        $this->assertNotEmpty(config('app.weatherapi.key'));
    }


    /** @test */
    public function test_weather_api()
    {
        $url = config('app.weatherapi.forecast') . '?key=' . config('app.weatherapi.key') . '&q=48.866,2.355&days=2';

        $response = Http::get($url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEquals(0, count($response->json()));

        //TODO change assertions according real data
        $this->assertContains('Paris', $response->json());
        $this->assertArrayHasKey('city', $response->json());
        $this->assertArrayHasKey('today', $response->json());
        $this->assertArrayHasKey('tomorrow', $response->json());
    }

    /** @test */
    public function test_fake_weather_api()
    {
        $url = config('app.weatherapi.forecast') . '?key=' . config('app.weatherapi.key') . '&q=48.866,2.355&days=2';

        Http::fake([
            $url => $this->fakeWeatherApi()
        ]);

        $url = config('app.weatherapi.forecast') . '?key=' . config('app.weatherapi.key') . '&q=48.866,2.355&days=2';

        $response = Http::get($url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEquals(0, count($response->json()));

        //TODO change assertions according real data
        $this->assertContains('Fake Paris', $response->json());
        $this->assertArrayHasKey('city', $response->json());
        $this->assertArrayHasKey('today', $response->json());
        $this->assertArrayHasKey('tomorrow', $response->json());
    }

    /** @test */
    public function test_console_way()
    {
        Http::fake([
            config('app.musement.cities') => $this->fakeMusementApi()
        ]);

        $url = config('app.weatherapi.forecast') . '?key=' . config('app.weatherapi.key') . '&q=48.866,2.355&days=2';

        Http::fake([
            $url => $this->fakeWeatherApi()
        ]);

        $this->assertEquals(0, $this->artisan('forecast') );
    }

    /** @test */
    public function test_browser_way()
    {
        Http::fake([
            config('app.musement.cities') => $this->fakeMusementApi()
        ]);

        $url = config('app.weatherapi.forecast') . '?key=' . config('app.weatherapi.key') . '&q=48.866,2.355&days=2';

        Http::fake([
            $url => $this->fakeWeatherApi()
        ]);

        $response = $this->call('GET', '/');

        $this->assertEquals(200, $response->status());
        $response->assertSee('City Fake Paris');
        $response->assertSee('Sunny');
        $response->assertSee('Partly cloudy');
    }

    /**
     * Fake data like from MusementApi
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    private function fakeMusementApi()
    {
        return Http::response([
            [
                "id" => 57,
                "top" => false,
                "name" => "Fake Amsterdam",
                "code" => "amsterdam",
                "content" => "Amsterdam is Holland’s capital city, a cultural hub and one of the Europe’s favorite travel destinations. It may not be an enormous city but its unique features are enough to attract tourists for day trips and longer holidays.Built under sea level, it is defined a ‘Venice of the North’ due to its many canals – it was a city built in the year 1000, reclaiming the area from advancing waters. Some of the most popular attractions in Amsterdam are the Rijksmuseum with Rembrant's famous painting 'The Night Watch', the Van Gogh Museum, Anne Frank's House, Museum Willet, the Cromhout Houses and many more.",
                "meta_description" => "Book your tickets for museums, tours and attractions in Amsterdam. Discover the Rijksmuseum, sip a beer at the Heineken Experience or take a tour on the canals.",
                "meta_title" => "Things to do in Amsterdam: Museums, tours, and attractions",
                "headline" => "Things to do in Amsterdam",
                "more" => "Young people come to this city for its nightlife and possibly to see the world renown “Coffee Shops”, while art-lovers on the other hand come to enjoy some of the city’s museums and the beautiful architecture. Holland successfully made its very own Renaissance architecture in the 17th century, giving Amsterdam its very own unique atmosphere.",
                "weight" => 20,
                "latitude" => 52.374,
                "longitude" => 4.9,
                "country" => [
                    "id" => 124,
                    "name" => "Netherlands",
                    "iso_code" => "NL",
                ],
                "cover_image_url" => "https://images.musement.com/cover/0002/15/amsterdam_header-114429.jpeg",
                "url" => "https://www.musement.com/us/amsterdam/",
                "activities_count" => 219,
                "time_zone" => "Europe/Amsterdam",
                "list_count" => 1,
                "venue_count" => 22,
                "show_in_popular" => true,
            ],
            [
                "id" => 40,
                "top" => true,
                "name" => "Fake Paris",
                "code" => "paris",
                "content" => "Do you really need a reason? The City of Lights means the Eiffel Tower, the Pompidou Center, the Louvre, the Musée d’Orsay, the Arc de Triomphe, Versaille, Montmartre, the Pantheon and Notre Dame. Then there’s the food, the street life, the history . . . Alas, you’re not the only person arriving in Paris – it pays to do some planning. In Paris, the quality of your experience can depend on the kind of ticket you have. Avoiding the crowds is good. Privileged access is good. Expert guides can reveal more than your guidebook could ever tell you. Sometimes, you discover that a nighttime or early-morning visit is quieter and more atmospheric. If you’re going to ‘do’ Paris, do it right. At the same time, remember there’s a whole other city waiting to be explored. In the laid-back districts of Belleville and Ménilmontant, you can tour the distinctive street art and learn more about such Parisian legends as Seth, Clet, Mesnager or Invader. A historical walk could introduce you to a bloodier history of massacres, executions, plague, torture and imprisonment. At the Fragonard Perfume Museum, you can create your own cologne. War enthusiasts, meanwhile, may be tempted to visit the nearby beaches of Normandy and see the D-Day battlefields first hand. The Dôme des Invalides allows the martial-minded to feast on the activities of Napoleon Bonaparte and explore one of the world’s greatest collections of antique arms and armor.",
                "meta_description" => "Discover Paris and book tickets for tours, attractions and museums. Climb the Eiffel Tower, cruise along the Seine river, or go on a guided tour of the Louvre Museum!",
                "meta_title" => "Things to do in Paris: Museums, tours, and attractions",
                "headline" => "Things to do in Paris",
                "more" => "",
                "weight" => 19,
                "latitude" => 48.866,
                "longitude" => 2.355,
                "country" => [
                    "id" => 60,
                    "name" => "France",
                    "iso_code" => "FR",
                ],
                "cover_image_url" => "https://images.musement.com/cover/0002/49/aerial-wide-angle-cityscape-view-of-paris-xxl-jpg_header-148745.jpeg",
                "url" => "https://www.musement.com/us/paris/",
                "activities_count" => 448,
                "time_zone" => "Europe/Paris",
                "list_count" => 1,
                "venue_count" => 46,
                "show_in_popular" => true
            ],
            [
                "id" => 2,
                "top" => true,
                "name" => "Fake Rome",
                "code" => "rome",
                "content" => "Rome is without doubt the largest and most impressive open-air museum in the world. The capital of Italy, the first great metropolis of humanity and one of the larger cities of Europe, Rome condenses its three millennia of history in architectural and artistic testimonies that leave you breathless, with masterpieces that make it one of the most visited cities in the world. Some say that one life is not enough to discover all the treasures hidden inside this incredible city. Just one day however is enough to be seduced by the exciting charm of the old capital of one of the most important ancient civilizations, the Roman Empire. Rome is the city with the highest concentration of historical and architectural landmarks in the world. The list of monuments and places worth visiting would be too long to mention, but some really just cannot be missed. Starting from the Colosseum, the largest amphitheater of the Roman world, recognized as one of the seven wonders of the modern world and the only one in Europe.",
                "meta_description" => "Discover Rome and book tickets to museums and attractions. Step inside the Colosseum, explore the famous Vatican Museums, or enjoy a guided walking tour of the city!",
                "meta_title" => "Things to do in Rome: Tours, museums and attractions",
                "headline" => "Things to do in Rome",
                "more" => "Not far off is the Roman Forum, what remains of the financial, religious and political center of ancient Rome, the heart of the urban complex of the Imperial Forums, which extended between the Capitoline Hill and the Quirinal. A city with beautiful gardens and ancient aristocratic villas, like the Villa Borghese, Rome is also famous for its monumental fountains, real architectural gems. The most famous is the Trevi Fountain, in rococo style, that is known for being the setting of the most famous scene of 'La dolce vita', classic italian movie by Federico Fellini. Do not forget to throw a coin into this fountain. Legend has it that this will assure your return to the \"eternal city\"! Don't miss the Baroque Fountain of the Four Rivers, one of the greatest masterpieces of Gian Lorenzo Bernini, located in the heart of Piazza Navona, elegant meeting point in the evening. Piazza di Spagna is also very crowded and popular, with its Spanish Steps. Capital with beautiful churches, Rome includes the Vatican City, the cradle of Catholic Christianity. Its masterpiece is the imposing St. Peter's Basilica, whose dome, designed by Michelangelo, overlooks the whole of Rome. Michelangelo also signed the famous frescoes of the Sistine Chapel, the conclave for the Pope's election. The heart of nightlife in this pleasure-seeking city is Trastevere, a former working-class district on the west bank of the Tiber River, a beautiful and lively maze of narrow streets and trendy bars.",
                "weight" => 12,
                "latitude" => 41.898,
                "longitude" => 12.483,
                "country" => [
                    "id" => 82,
                    "name" => "Italy",
                    "iso_code" => "IT",
                ],
                "cover_image_url" => "https://images.musement.com/cover/0002/37/top-view-of-rome-city-skyline-from-castel-sant-angelo-jpg_header-136539.jpeg",
                "url" => "https://www.musement.com/us/rome/",
                "activities_count" => 580,
                "time_zone" => "Europe/Rome",
                "list_count" => 1,
                "venue_count" => 20,
                "show_in_popular" => true,
            ],
            [
                "id" => 1,
                "top" => false,
                "name" => "Fake Milan",
                "code" => "milan",
                "content" => "City of culture. City of fashion. City of heavenly ice creams and yellow rice. If you listen to some people, Milan is a place where you’ll be ostracized for wearing a tracksuit in public. In truth, this is a great northern Italian city with as many faces as a fashionista. Leonardo da Vinci lived here for a while. You can visit the very vineyard where he relaxed while painting the Last Supper. Tickets can be hard to obtain for this most famous of meals, so it’s best to plan in advance. Afterwards, visit the Leonardo3 Museum, where you can learn more about the master’s life and see models of his creations. Milan rewards walking. From the Piazza Duomo, you can tour the colossal cathedral, the monumental Galleria Vittorio Emanuele II, majestic La Scala and the old district of La Brera. Sforzesco Castle is a little further if you’d like to see the grandeur of Milan’s ruling dukes, while the city’s art galleries are a real treat for their permanent and ever-changing temporary exhibitions. If you need more labels in your life, take off the tracksuit and stroll the exclusive Via Monte Napoleone. Then, when you tire of past glories, go to the brand new Piazza Gae Aulenti to see the Unicredit Tower designed by architect César Pell. Beyond the city center, Milan’s districts offer different perspectives. Navigli is a cool network of canals whose bars and restaurants cast dancing reflections off the water at night. The previously blue-collar neighborhood of Isola has been reborn as a leafy oasis of notable street art, cafes, bars, boutiques, galleries and clubs – basically, hipster central. Citta Studi around Piazza Leonardo is where the students congregate, and therefore where you’ll find some good craft beers. If Milan’s charms fade (unlikely), you’re in the middle of a region that offers you easy excursions to beautiful Lake Como, Verona, the wine country of Pavia and even Venice. The dolce vita begins here...",
                "meta_description" => "Discover Milan and book tickets to the best tours, museums, and attractions. Admire the Duomo of Milan, visit Leonardo's Last Supper or go on a city walking tour.",
                "meta_title" => "Things to do in Milan: Tours, museums and attractions",
                "headline" => "Things to do in Milan",
                "weight" => 11,
                "latitude" => 45.459,
                "longitude" => 9.183,
                "country" => [
                    "id" => 82,
                    "name" => "Italy",
                    "iso_code" => "IT",
                ],
                "cover_image_url" => "https://images.musement.com/cover/0002/39/milan-vittorio-emanuele-ii-gallery-italy-jpg_header-138313.jpeg",
                "url" => "https://www.musement.com/us/milan/",
                "activities_count" => 91,
                "time_zone" => "Europe/Rome",
                "list_count" => 1,
                "venue_count" => 21,
                "show_in_popular" => true,
            ]
        ]);
    }

    /**
     * Fake data like from WeatherApi
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    private function fakeWeatherApi()
    {
        //TODO change response according real data
        return Http::response(
            [
                "city" => "Fake Paris",
                "today" => "Sunny",
                "tomorrow" => "Partly cloudy",
            ]
        );
    }
}
