<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // Texas
            ['state' => 'TX', 'name' => 'Houston', 'slug' => 'houston-tx', 'area_codes' => '713,281,832', 'population' => 2304580, 'latitude' => 29.7604, 'longitude' => -95.3698, 'priority' => 5,
                'nearby_cities' => ['Katy', 'Sugar Land', 'Pearland', 'Pasadena', 'The Woodlands', 'Spring', 'Cypress', 'League City', 'Missouri City', 'Baytown'],
                'climate_info' => 'a hot, humid subtropical climate with mild winters, making outdoor events and construction possible year-round',
                'local_events' => 'the Houston Livestock Show and Rodeo, Houston Art Car Parade, and numerous outdoor festivals',
                'construction_info' => 'one of the largest construction markets in the US with continuous residential, commercial, and industrial development',
            ],
            ['state' => 'TX', 'name' => 'Dallas', 'slug' => 'dallas-tx', 'area_codes' => '214,469,972', 'population' => 1304379, 'latitude' => 32.7767, 'longitude' => -96.7970, 'priority' => 5,
                'nearby_cities' => ['Fort Worth', 'Arlington', 'Plano', 'Irving', 'Garland', 'Frisco', 'McKinney', 'Grand Prairie', 'Mesquite', 'Denton'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'the State Fair of Texas, Dallas Arts District events, and numerous outdoor concerts and festivals',
                'construction_info' => 'a rapidly growing metropolitan area with massive construction activity in both residential and commercial sectors',
            ],
            ['state' => 'TX', 'name' => 'San Antonio', 'slug' => 'san-antonio-tx', 'area_codes' => '210', 'population' => 1434625, 'latitude' => 29.4241, 'longitude' => -98.4936, 'priority' => 4,
                'nearby_cities' => ['New Braunfels', 'Schertz', 'Converse', 'Universal City', 'Live Oak', 'Selma', 'Cibolo', 'Boerne', 'Seguin', 'Helotes'],
                'climate_info' => 'a warm climate with hot summers ideal for outdoor events and year-round construction',
                'local_events' => 'Fiesta San Antonio, Battle of Flowers Parade, and numerous cultural festivals',
                'construction_info' => 'a booming construction market driven by population growth and military base expansions',
            ],

            // Tennessee
            ['state' => 'TN', 'name' => 'Nashville', 'slug' => 'nashville-tn', 'area_codes' => '615,629', 'population' => 689447, 'latitude' => 36.1627, 'longitude' => -86.7816, 'priority' => 4,
                'nearby_cities' => ['Murfreesboro', 'Franklin', 'Hendersonville', 'Gallatin', 'Lebanon', 'Mount Juliet', 'Smyrna', 'La Vergne', 'Brentwood', 'Spring Hill'],
                'climate_info' => 'a humid subtropical climate with warm summers perfect for outdoor events',
                'local_events' => 'CMA Fest, Bonnaroo (nearby), Nashville Film Festival, and countless live music events',
                'construction_info' => 'one of the fastest-growing cities in the US with extensive construction activity',
            ],
            ['state' => 'TN', 'name' => 'Memphis', 'slug' => 'memphis-tn', 'area_codes' => '901', 'population' => 633104, 'latitude' => 35.1495, 'longitude' => -90.0490, 'priority' => 3,
                'nearby_cities' => ['Germantown', 'Bartlett', 'Collierville', 'Lakeland', 'Arlington', 'Millington', 'Olive Branch', 'Southaven', 'Horn Lake', 'Cordova'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Beale Street Music Festival, Memphis in May, and numerous BBQ competitions',
                'construction_info' => 'ongoing urban revitalization and suburban development projects',
            ],
            ['state' => 'TN', 'name' => 'Knoxville', 'slug' => 'knoxville-tn', 'area_codes' => '865', 'population' => 190740, 'latitude' => 35.9606, 'longitude' => -83.9207, 'priority' => 2,
                'nearby_cities' => ['Maryville', 'Farragut', 'Oak Ridge', 'Sevierville', 'Powell', 'Lenoir City', 'Clinton', 'Alcoa', 'Jefferson City', 'Morristown'],
                'climate_info' => 'a humid subtropical climate with four distinct seasons',
                'local_events' => 'Dogwood Arts Festival, Big Ears Festival, and University of Tennessee football tailgating',
                'construction_info' => 'steady growth in residential and commercial construction',
            ],
            ['state' => 'TN', 'name' => 'Chattanooga', 'slug' => 'chattanooga-tn', 'area_codes' => '423', 'population' => 181099, 'latitude' => 35.0456, 'longitude' => -85.3097, 'priority' => 2,
                'nearby_cities' => ['East Ridge', 'Red Bank', 'Signal Mountain', 'Soddy-Daisy', 'Hixson', 'Ooltewah', 'Cleveland', 'Ringgold', 'Fort Oglethorpe', 'Jasper'],
                'climate_info' => 'a humid subtropical climate with warm summers',
                'local_events' => 'Riverbend Festival, 4 Bridges Arts Festival, and numerous outdoor adventure events',
                'construction_info' => 'growing tech hub with increasing construction and development',
            ],

            // North Carolina
            ['state' => 'NC', 'name' => 'Charlotte', 'slug' => 'charlotte-nc', 'area_codes' => '704,980', 'population' => 874579, 'latitude' => 35.2271, 'longitude' => -80.8431, 'priority' => 4,
                'nearby_cities' => ['Concord', 'Gastonia', 'Huntersville', 'Mooresville', 'Matthews', 'Mint Hill', 'Indian Trail', 'Cornelius', 'Kannapolis', 'Rock Hill'],
                'climate_info' => 'a humid subtropical climate with warm summers and mild winters',
                'local_events' => 'Charlotte Festival of Food, Speed Street, and numerous NASCAR events',
                'construction_info' => 'a major banking hub with significant commercial and residential construction',
            ],
            ['state' => 'NC', 'name' => 'Raleigh', 'slug' => 'raleigh-nc', 'area_codes' => '919,984', 'population' => 467665, 'latitude' => 35.7796, 'longitude' => -78.6382, 'priority' => 3,
                'nearby_cities' => ['Durham', 'Cary', 'Chapel Hill', 'Apex', 'Wake Forest', 'Holly Springs', 'Garner', 'Fuquay-Varina', 'Morrisville', 'Knightdale'],
                'climate_info' => 'a humid subtropical climate with four seasons',
                'local_events' => 'North Carolina State Fair, Hopscotch Music Festival, and numerous outdoor events',
                'construction_info' => 'Research Triangle area with booming tech-driven construction',
            ],

            // Georgia
            ['state' => 'GA', 'name' => 'Atlanta', 'slug' => 'atlanta-ga', 'area_codes' => '404,470,678,770', 'population' => 498715, 'latitude' => 33.7490, 'longitude' => -84.3880, 'priority' => 5,
                'nearby_cities' => ['Marietta', 'Sandy Springs', 'Roswell', 'Alpharetta', 'Decatur', 'Kennesaw', 'Lawrenceville', 'Duluth', 'Smyrna', 'Peachtree City'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Music Midtown, Atlanta Dogwood Festival, and Dragon Con',
                'construction_info' => 'a major southeastern hub with extensive infrastructure and development projects',
            ],
            ['state' => 'GA', 'name' => 'Savannah', 'slug' => 'savannah-ga', 'area_codes' => '912', 'population' => 147780, 'latitude' => 32.0809, 'longitude' => -81.0912, 'priority' => 2,
                'nearby_cities' => ['Pooler', 'Richmond Hill', 'Rincon', 'Garden City', 'Port Wentworth', 'Bloomingdale', 'Thunderbolt', 'Tybee Island', 'Georgetown', 'Hinesville'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Savannah Music Festival, St. Patrick\'s Day Parade, and numerous historic district events',
                'construction_info' => 'growing tourism and port-driven construction activity',
            ],

            // Florida
            ['state' => 'FL', 'name' => 'Jacksonville', 'slug' => 'jacksonville-fl', 'area_codes' => '904', 'population' => 949611, 'latitude' => 30.3322, 'longitude' => -81.6557, 'priority' => 3,
                'nearby_cities' => ['Orange Park', 'St. Augustine', 'Fernandina Beach', 'Atlantic Beach', 'Neptune Beach', 'Jacksonville Beach', 'Ponte Vedra', 'Fleming Island', 'Middleburg', 'Green Cove Springs'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters ideal for year-round outdoor activities',
                'local_events' => 'Jacksonville Jazz Festival, One Spark Festival, and numerous beach events',
                'construction_info' => 'rapid residential and commercial growth as one of Florida\'s largest cities by area',
            ],

            // Indiana
            ['state' => 'IN', 'name' => 'Indianapolis', 'slug' => 'indianapolis-in', 'area_codes' => '317,463', 'population' => 887642, 'latitude' => 39.7684, 'longitude' => -86.1581, 'priority' => 3,
                'nearby_cities' => ['Carmel', 'Fishers', 'Greenwood', 'Lawrence', 'Plainfield', 'Brownsburg', 'Avon', 'Zionsville', 'Noblesville', 'Westfield'],
                'climate_info' => 'a humid continental climate with warm summers and cold winters',
                'local_events' => 'Indianapolis 500, Indiana State Fair, Gen Con, and numerous motorsport events',
                'construction_info' => 'steady growth in suburban development and downtown revitalization',
            ],

            // Ohio
            ['state' => 'OH', 'name' => 'Columbus', 'slug' => 'columbus-oh', 'area_codes' => '614,380', 'population' => 905748, 'latitude' => 39.9612, 'longitude' => -82.9988, 'priority' => 3,
                'nearby_cities' => ['Dublin', 'Westerville', 'Grove City', 'Hilliard', 'Reynoldsburg', 'Gahanna', 'Upper Arlington', 'New Albany', 'Pickerington', 'Delaware'],
                'climate_info' => 'a humid continental climate with warm summers',
                'local_events' => 'Ohio State Fair, Columbus Arts Festival, and Ohio State football tailgating',
                'construction_info' => 'one of the fastest-growing cities in the Midwest with significant construction activity',
            ],

            // Oklahoma
            ['state' => 'OK', 'name' => 'Oklahoma City', 'slug' => 'oklahoma-city-ok', 'area_codes' => '405', 'population' => 681054, 'latitude' => 35.4676, 'longitude' => -97.5164, 'priority' => 3,
                'nearby_cities' => ['Edmond', 'Norman', 'Moore', 'Midwest City', 'Del City', 'Yukon', 'Mustang', 'Bethany', 'Choctaw', 'Newcastle'],
                'climate_info' => 'a humid subtropical climate with hot summers and variable weather',
                'local_events' => 'Oklahoma State Fair, Festival of the Arts, and numerous rodeo events',
                'construction_info' => 'growing energy sector driving commercial and residential construction',
            ],
            ['state' => 'OK', 'name' => 'Tulsa', 'slug' => 'tulsa-ok', 'area_codes' => '918,539', 'population' => 413066, 'latitude' => 36.1540, 'longitude' => -95.9928, 'priority' => 2,
                'nearby_cities' => ['Broken Arrow', 'Owasso', 'Bixby', 'Jenks', 'Sand Springs', 'Sapulpa', 'Claremore', 'Catoosa', 'Glenpool', 'Coweta'],
                'climate_info' => 'a humid subtropical climate with hot summers',
                'local_events' => 'Tulsa State Fair, Mayfest, and Gathering Place events',
                'construction_info' => 'revitalizing downtown area with new construction projects',
            ],

            // Alabama
            ['state' => 'AL', 'name' => 'Birmingham', 'slug' => 'birmingham-al', 'area_codes' => '205,659', 'population' => 200733, 'latitude' => 33.5207, 'longitude' => -86.8025, 'priority' => 2,
                'nearby_cities' => ['Hoover', 'Vestavia Hills', 'Homewood', 'Mountain Brook', 'Trussville', 'Bessemer', 'Alabaster', 'Pelham', 'Helena', 'Gardendale'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Sloss Music & Arts Festival, Birmingham Restaurant Week, and numerous outdoor events',
                'construction_info' => 'ongoing urban renewal and medical district expansion',
            ],
            ['state' => 'AL', 'name' => 'Huntsville', 'slug' => 'huntsville-al', 'area_codes' => '256,938', 'population' => 215006, 'latitude' => 34.7304, 'longitude' => -86.5861, 'priority' => 2,
                'nearby_cities' => ['Madison', 'Decatur', 'Athens', 'Harvest', 'Meridianville', 'Hazel Green', 'Owens Cross Roads', 'New Market', 'Gurley', 'Toney'],
                'climate_info' => 'a humid subtropical climate with warm summers',
                'local_events' => 'Panoply Arts Festival, Big Spring Jam, and Rocket City events',
                'construction_info' => 'one of the fastest-growing cities in Alabama with aerospace-driven development',
            ],

            // Kentucky
            ['state' => 'KY', 'name' => 'Louisville', 'slug' => 'louisville-ky', 'area_codes' => '502', 'population' => 633045, 'latitude' => 38.2527, 'longitude' => -85.7585, 'priority' => 3,
                'nearby_cities' => ['Jeffersontown', 'Shively', 'Shepherdsville', 'Elizabethtown', 'Radcliff', 'La Grange', 'Shelbyville', 'Mount Washington', 'Bardstown', 'Frankfort'],
                'climate_info' => 'a humid subtropical climate with warm summers and cool winters',
                'local_events' => 'Kentucky Derby, Forecastle Festival, and numerous bourbon trail events',
                'construction_info' => 'steady construction growth in both urban and suburban areas',
            ],
            ['state' => 'KY', 'name' => 'Lexington', 'slug' => 'lexington-ky', 'area_codes' => '859', 'population' => 322570, 'latitude' => 38.0406, 'longitude' => -84.5037, 'priority' => 2,
                'nearby_cities' => ['Georgetown', 'Nicholasville', 'Richmond', 'Winchester', 'Versailles', 'Paris', 'Berea', 'Danville', 'Mount Sterling', 'Wilmore'],
                'climate_info' => 'a humid subtropical climate with four distinct seasons',
                'local_events' => 'Keeneland horse racing, Festival of the Bluegrass, and numerous equestrian events',
                'construction_info' => 'growing university town with steady residential and commercial development',
            ],

            // Virginia
            ['state' => 'VA', 'name' => 'Richmond', 'slug' => 'richmond-va', 'area_codes' => '804', 'population' => 226610, 'latitude' => 37.5407, 'longitude' => -77.4360, 'priority' => 3,
                'nearby_cities' => ['Henrico', 'Chesterfield', 'Midlothian', 'Glen Allen', 'Mechanicsville', 'Chester', 'Short Pump', 'Ashland', 'Petersburg', 'Colonial Heights'],
                'climate_info' => 'a humid subtropical climate with warm summers and mild winters',
                'local_events' => 'Richmond Folk Festival, State Fair of Virginia, and numerous outdoor events along the James River',
                'construction_info' => 'growing tech and healthcare sectors driving construction activity',
            ],

            // South Carolina
            ['state' => 'SC', 'name' => 'Greenville', 'slug' => 'greenville-sc', 'area_codes' => '864', 'population' => 72095, 'latitude' => 34.8526, 'longitude' => -82.3940, 'priority' => 2,
                'nearby_cities' => ['Spartanburg', 'Greer', 'Simpsonville', 'Mauldin', 'Easley', 'Anderson', 'Taylors', 'Travelers Rest', 'Fountain Inn', 'Piedmont'],
                'climate_info' => 'a humid subtropical climate with warm summers and mild winters',
                'local_events' => 'Fall for Greenville, Artisphere, and numerous downtown events',
                'construction_info' => 'one of the fastest-growing areas in South Carolina with automotive manufacturing driving development',
            ],
            ['state' => 'SC', 'name' => 'Columbia', 'slug' => 'columbia-sc', 'area_codes' => '803', 'population' => 136632, 'latitude' => 34.0007, 'longitude' => -81.0348, 'priority' => 2,
                'nearby_cities' => ['Irmo', 'Lexington', 'West Columbia', 'Cayce', 'Blythewood', 'Chapin', 'Elgin', 'Northeast Columbia', 'Forest Acres', 'Springdale'],
                'climate_info' => 'a humid subtropical climate with hot summers',
                'local_events' => 'South Carolina State Fair, Famously Hot New Year, and University of South Carolina events',
                'construction_info' => 'state capital with steady government and university-driven construction',
            ],

            // Louisiana
            ['state' => 'LA', 'name' => 'Baton Rouge', 'slug' => 'baton-rouge-la', 'area_codes' => '225', 'population' => 227470, 'latitude' => 30.4515, 'longitude' => -91.1871, 'priority' => 2,
                'nearby_cities' => ['Denham Springs', 'Gonzales', 'Zachary', 'Central', 'Baker', 'Port Allen', 'Prairieville', 'Walker', 'Shenandoah', 'Plaquemine'],
                'climate_info' => 'a humid subtropical climate with hot, humid summers and mild winters',
                'local_events' => 'LSU football tailgating, Baton Rouge Blues Festival, and numerous Cajun cultural events',
                'construction_info' => 'petrochemical industry and university driving steady construction growth',
            ],

            // Colorado
            ['state' => 'CO', 'name' => 'Denver', 'slug' => 'denver-co', 'area_codes' => '303,720', 'population' => 715522, 'latitude' => 39.7392, 'longitude' => -104.9903, 'priority' => 4,
                'nearby_cities' => ['Aurora', 'Lakewood', 'Thornton', 'Arvada', 'Westminster', 'Centennial', 'Boulder', 'Broomfield', 'Littleton', 'Highlands Ranch'],
                'climate_info' => 'a semi-arid continental climate with abundant sunshine and outdoor recreation opportunities',
                'local_events' => 'Great American Beer Festival, Denver PrideFest, and numerous outdoor music festivals',
                'construction_info' => 'one of the hottest construction markets in the US with rapid population growth',
            ],

            // Nebraska
            ['state' => 'NE', 'name' => 'Omaha', 'slug' => 'omaha-ne', 'area_codes' => '402,531', 'population' => 486051, 'latitude' => 41.2565, 'longitude' => -95.9345, 'priority' => 2,
                'nearby_cities' => ['Bellevue', 'Papillion', 'La Vista', 'Ralston', 'Gretna', 'Elkhorn', 'Council Bluffs', 'Bennington', 'Fremont', 'Blair'],
                'climate_info' => 'a humid continental climate with warm summers and cold winters',
                'local_events' => 'College World Series, Omaha Summer Arts Festival, and Berkshire Hathaway annual meeting events',
                'construction_info' => 'steady growth in suburban development and downtown revitalization',
            ],

            // Arizona
            ['state' => 'AZ', 'name' => 'Phoenix', 'slug' => 'phoenix-az', 'area_codes' => '602,480,623', 'population' => 1608139, 'latitude' => 33.4484, 'longitude' => -112.0740, 'priority' => 5,
                'nearby_cities' => ['Scottsdale', 'Mesa', 'Tempe', 'Chandler', 'Gilbert', 'Glendale', 'Peoria', 'Surprise', 'Goodyear', 'Avondale'],
                'climate_info' => 'a hot desert climate with extremely hot summers — outdoor events require proper sanitation planning',
                'local_events' => 'Arizona State Fair, Waste Management Phoenix Open, and numerous spring training baseball events',
                'construction_info' => 'one of the fastest-growing metros in the US with massive construction activity year-round',
            ],
            ['state' => 'AZ', 'name' => 'Tucson', 'slug' => 'tucson-az', 'area_codes' => '520', 'population' => 542629, 'latitude' => 32.2226, 'longitude' => -110.9747, 'priority' => 2,
                'nearby_cities' => ['Marana', 'Oro Valley', 'Sahuarita', 'South Tucson', 'Catalina Foothills', 'Casas Adobes', 'Flowing Wells', 'Drexel Heights', 'Green Valley', 'Vail'],
                'climate_info' => 'a hot semi-arid climate with very hot summers',
                'local_events' => 'Tucson Gem and Mineral Show, Tucson Festival of Books, and numerous desert events',
                'construction_info' => 'growing university town with increasing residential development',
            ],

            // Arkansas
            ['state' => 'AR', 'name' => 'Little Rock', 'slug' => 'little-rock-ar', 'area_codes' => '501', 'population' => 202591, 'latitude' => 34.7465, 'longitude' => -92.2896, 'priority' => 2,
                'nearby_cities' => ['North Little Rock', 'Conway', 'Jacksonville', 'Benton', 'Bryant', 'Sherwood', 'Cabot', 'Maumelle', 'Searcy', 'Hot Springs'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Riverfest, Arkansas State Fair, and numerous outdoor events along the Arkansas River',
                'construction_info' => 'state capital with steady government and healthcare construction projects',
            ],
        ];

        foreach ($cities as $cityData) {
            $stateCode = $cityData['state'];
            unset($cityData['state']);

            $state = State::where('code', $stateCode)->first();

            if ($state) {
                $cityData['state_id'] = $state->id;
                City::updateOrCreate(
                    ['slug' => $cityData['slug']],
                    $cityData
                );
            }
        }
    }
}
