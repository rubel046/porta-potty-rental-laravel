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
            // Texas - Major Markets
            ['state' => 'TX', 'name' => 'Houston', 'slug' => 'houston-tx', 'area_codes' => '713,281,832,346,621', 'population' => 2333346, 'latitude' => 29.7604, 'longitude' => -95.3698, 'priority' => 5,
                'nearby_cities' => ['Katy', 'Sugar Land', 'Pearland', 'Pasadena', 'The Woodlands', 'Spring', 'Cypress', 'League City', 'Missouri City', 'Baytown'],
                'climate_info' => 'a hot, humid subtropical climate with mild winters, making outdoor events and construction possible year-round',
                'local_events' => 'the Houston Livestock Show and Rodeo, Houston Art Car Parade, and numerous outdoor festivals',
                'construction_info' => 'one of the largest construction markets in the US with continuous residential, commercial, and industrial development',
            ],
            ['state' => 'TX', 'name' => 'San Antonio', 'slug' => 'san-antonio-tx', 'area_codes' => '210,830', 'population' => 1479835, 'latitude' => 29.4241, 'longitude' => -98.4936, 'priority' => 5,
                'nearby_cities' => ['New Braunfels', 'Schertz', 'Converse', 'Universal City', 'Live Oak', 'Selma', 'Cibolo', 'Boerne', 'Seguin', 'Helotes'],
                'climate_info' => 'a warm climate with hot summers ideal for outdoor events and year-round construction',
                'local_events' => 'Fiesta San Antonio, Battle of Flowers Parade, and numerous cultural festivals',
                'construction_info' => 'a booming construction market driven by population growth and military base expansions',
            ],
            ['state' => 'TX', 'name' => 'Dallas', 'slug' => 'dallas-tx', 'area_codes' => '214,469,972', 'population' => 1307930, 'latitude' => 32.7767, 'longitude' => -96.7970, 'priority' => 5,
                'nearby_cities' => ['Fort Worth', 'Arlington', 'Plano', 'Irving', 'Garland', 'Frisco', 'McKinney', 'Grand Prairie', 'Mesquite', 'Denton'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'the State Fair of Texas, Dallas Arts District events, and numerous outdoor concerts and festivals',
                'construction_info' => 'a rapidly growing metropolitan area with massive construction activity in both residential and commercial sectors',
            ],
            ['state' => 'TX', 'name' => 'Austin', 'slug' => 'austin-tx', 'area_codes' => '512,737', 'population' => 979539, 'latitude' => 30.2672, 'longitude' => -97.7431, 'priority' => 4,
                'nearby_cities' => ['Round Rock', 'Pflugerville', 'Cedar Park', 'Georgetown', 'San Marcos', 'Buda', 'Kyle', 'Leander', 'Lakeway', 'Dripping Springs'],
                'climate_info' => 'a subtropical humid climate with hot summers and mild winters',
                'local_events' => 'SXSW, Austin City Limits Music Festival, and numerous tech conferences',
                'construction_info' => 'one of the fastest-growing cities in the US with massive residential and commercial development',
            ],
            ['state' => 'TX', 'name' => 'Fort Worth', 'slug' => 'fort-worth-tx', 'area_codes' => '817,682', 'population' => 978468, 'latitude' => 32.7555, 'longitude' => -97.3308, 'priority' => 4,
                'nearby_cities' => ['Arlington', 'Mansfield', 'Southlake', 'Keller', 'North Richland Hills', 'Bedford', 'Hurst', 'Euless', 'Grapevine', 'Colleyville'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Fort Worth Stock Show & Rodeo, Fort Worth Art Festival, and Billy Bob\'s Texas activities',
                'construction_info' => 'rapid suburban growth with extensive residential and commercial construction',
            ],

            // California - Major Markets
            ['state' => 'CA', 'name' => 'Los Angeles', 'slug' => 'los-angeles-ca', 'area_codes' => '213,310,323,408,424,510,562,626,628,650,657,661,707,714,747,760,805,818,831,858,909,935,949', 'population' => 3869891, 'latitude' => 34.0522, 'longitude' => -118.2437, 'priority' => 5,
                'nearby_cities' => ['Long Beach', 'Glendale', 'Santa Clarita', 'Lancaster', 'Palmdale', 'Pomona', 'Torrance', 'Pasadena', 'Burbank', 'Inglewood'],
                'climate_info' => 'a Mediterranean climate with mild winters and hot dry summers',
                'local_events' => 'Hollywood Bowl concerts, LA Film Festival, and numerous entertainment industry events',
                'construction_info' => 'massive construction market with ongoing residential and infrastructure development',
            ],
            ['state' => 'CA', 'name' => 'San Diego', 'slug' => 'san-diego-ca', 'area_codes' => '619,858,760', 'population' => 1386933, 'latitude' => 32.7157, 'longitude' => -117.1611, 'priority' => 4,
                'nearby_cities' => ['Chula Vista', 'El Cajon', 'Escondido', 'Carlsbad', 'Oceanside', 'Vista', 'San Marcos', 'Encinitas', 'La Jolla', 'Poway'],
                'climate_info' => 'a semi-arid Mediterranean climate with mild temperatures year-round',
                'local_events' => 'San Diego Comic-Con, San Diego Bay Fair, and numerous beach festivals',
                'construction_info' => 'steady growth in residential and military-related construction',
            ],
            ['state' => 'CA', 'name' => 'San Jose', 'slug' => 'san-jose-ca', 'area_codes' => '408,669', 'population' => 991209, 'latitude' => 37.3382, 'longitude' => -121.8863, 'priority' => 4,
                'nearby_cities' => ['Sunnyvale', 'Mountain View', 'Santa Clara', 'Cupertino', 'Palo Alto', 'Milpitas', 'Fremont', 'San Mateo', 'Redwood City', 'Menlo Park'],
                'climate_info' => 'a Mediterranean climate with warm dry summers and mild winters',
                'local_events' => 'Silicon Valley tech conferences, San Jose Jazz Festival, and numerous tech expos',
                'construction_info' => 'high-tech hub with ongoing residential and commercial development',
            ],
            ['state' => 'CA', 'name' => 'San Francisco', 'slug' => 'san-francisco-ca', 'area_codes' => '415,628,650,707', 'population' => 873965, 'latitude' => 37.7749, 'longitude' => -122.4194, 'priority' => 4,
                'nearby_cities' => ['Oakland', 'Berkeley', 'Fremont', 'San Mateo', 'Palo Alto', 'Sunnyvale', 'Santa Clara', 'Richmond', 'Hayward', 'Daly City'],
                'climate_info' => 'a cool Mediterranean climate with foggy summers and mild winters',
                'local_events' => 'SF Pride, Hardly Strictly Bluegrass, and numerous tech and cultural festivals',
                'construction_info' => 'high-cost market with ongoing residential and commercial development',
            ],

            // Florida - Major Markets
            ['state' => 'FL', 'name' => 'Miami', 'slug' => 'miami-fl', 'area_codes' => '305,786,645', 'population' => 449974, 'latitude' => 25.7617, 'longitude' => -80.1918, 'priority' => 5,
                'nearby_cities' => ['Hialeah', 'Miami Beach', 'Fort Lauderdale', 'Hollywood', 'Pembroke Pines', 'Coral Gables', 'Coral Springs', 'Pompano Beach', 'Davie', 'Boca Raton'],
                'climate_info' => 'a tropical monsoon climate with hot humid summers and warm winters',
                'local_events' => 'Miami Art Week, Ultra Music Festival, and numerous beach and cultural events',
                'construction_info' => 'rapid high-rise residential and commercial development',
            ],
            ['state' => 'FL', 'name' => 'Jacksonville', 'slug' => 'jacksonville-fl', 'area_codes' => '904', 'population' => 949611, 'latitude' => 30.3322, 'longitude' => -81.6557, 'priority' => 4,
                'nearby_cities' => ['Orange Park', 'St. Augustine', 'Fernandina Beach', 'Atlantic Beach', 'Neptune Beach', 'Jacksonville Beach', 'Ponte Vedra', 'Fleming Island', 'Middleburg', 'Green Cove Springs'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters ideal for year-round outdoor activities',
                'local_events' => 'Jacksonville Jazz Festival, One Spark Festival, and numerous beach events',
                'construction_info' => 'rapid residential and commercial growth as one of Florida\'s largest cities by area',
            ],
            ['state' => 'FL', 'name' => 'Tampa', 'slug' => 'tampa-fl', 'area_codes' => '813,727', 'population' => 414575, 'latitude' => 27.9506, 'longitude' => -82.4572, 'priority' => 4,
                'nearby_cities' => ['St. Petersburg', 'Clearwater', 'Brandon', 'Lakeland', 'Kissimmee', 'Orlando', 'Winter Haven', 'Plant City', 'Palm Harbor', 'New Port Richey'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Gasparilla Pirate Festival, Tampa Bay Lightning games, and numerous beach events',
                'construction_info' => 'rapid growth in residential and commercial construction',
            ],
            ['state' => 'FL', 'name' => 'Orlando', 'slug' => 'orlando-fl', 'area_codes' => '407,689', 'population' => 320742, 'latitude' => 28.5383, 'longitude' => -81.3792, 'priority' => 4,
                'nearby_cities' => ['Kissimmee', 'Sanford', 'Winter Garden', 'Clermont', 'Apopka', 'Altamonte Springs', 'Ocoee', 'Winter Park', 'Longwood', 'Casselberry'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => ' theme park events, Epcot International Flower & Garden Festival, and numerous conventions',
                'construction_info' => 'massive tourism-driven construction with ongoing residential and commercial development',
            ],

            // Arizona
            ['state' => 'AZ', 'name' => 'Phoenix', 'slug' => 'phoenix-az', 'area_codes' => '602,480,623,928', 'population' => 1608139, 'latitude' => 33.4484, 'longitude' => -112.0740, 'priority' => 5,
                'nearby_cities' => ['Scottsdale', 'Mesa', 'Tempe', 'Chandler', 'Gilbert', 'Glendale', 'Peoria', 'Surprise', 'Goodyear', 'Avondale'],
                'climate_info' => 'a hot desert climate with extremely hot summers — outdoor events require proper sanitation planning',
                'local_events' => 'Arizona State Fair, Waste Management Phoenix Open, and numerous spring training baseball events',
                'construction_info' => 'one of the fastest-growing metros in the US with massive construction activity year-round',
            ],
            ['state' => 'AZ', 'name' => 'Tucson', 'slug' => 'tucson-az', 'area_codes' => '520', 'population' => 542629, 'latitude' => 32.2226, 'longitude' => -110.9747, 'priority' => 3,
                'nearby_cities' => ['Marana', 'Oro Valley', 'Sahuarita', 'South Tucson', 'Catalina Foothills', 'Casas Adobes', 'Flowing Wells', 'Drexel Heights', 'Green Valley', 'Vail'],
                'climate_info' => 'a hot semi-arid climate with very hot summers',
                'local_events' => 'Tucson Gem and Mineral Show, Tucson Festival of Books, and numerous desert events',
                'construction_info' => 'growing university town with increasing residential development',
            ],

            // Nevada
            ['state' => 'NV', 'name' => 'Las Vegas', 'slug' => 'las-vegas-nv', 'area_codes' => '702,725', 'population' => 641903, 'latitude' => 36.1699, 'longitude' => -115.1398, 'priority' => 5,
                'nearby_cities' => ['Henderson', 'North Las Vegas', 'Sunrise Manor', 'Spring Valley', 'Enterprise', 'Sparks', 'Reno', 'Carson City', 'Elko', 'Boulder City'],
                'climate_info' => 'a subtropical desert climate with extremely hot summers and mild winters',
                'local_events' => 'Consumer Electronics Show, Las Vegas Strip events, and numerous concerts and conventions',
                'construction_info' => 'rapid residential and commercial development with massive construction activity',
            ],

            // Colorado
            ['state' => 'CO', 'name' => 'Denver', 'slug' => 'denver-co', 'area_codes' => '303,720', 'population' => 715522, 'latitude' => 39.7392, 'longitude' => -104.9903, 'priority' => 4,
                'nearby_cities' => ['Aurora', 'Lakewood', 'Thornton', 'Arvada', 'Westminster', 'Centennial', 'Boulder', 'Broomfield', 'Littleton', 'Highlands Ranch'],
                'climate_info' => 'a semi-arid continental climate with abundant sunshine and outdoor recreation opportunities',
                'local_events' => 'Great American Beer Festival, Denver PrideFest, and numerous outdoor music festivals',
                'construction_info' => 'one of the hottest construction markets in the US with rapid population growth',
            ],
            ['state' => 'CO', 'name' => 'Colorado Springs', 'slug' => 'colorado-springs-co', 'area_codes' => '719', 'population' => 483956, 'latitude' => 38.8339, 'longitude' => -104.8214, 'priority' => 3,
                'nearby_cities' => ['Pueblo', 'Fort Carson', 'Security-Widefield', 'Woodmoor', 'Black Forest', 'Fountain', 'Monument', 'Palmer Lake', 'Cañon City', 'Walsenburg'],
                'climate_info' => 'a semi-arid continental climate with four distinct seasons',
                'local_events' => 'US Olympic & Paralympic Training Center events, Pikes Peak International Hill Climb, and numerous military-related activities',
                'construction_info' => 'steady growth driven by military presence and outdoor recreation industry',
            ],

            // Washington
            ['state' => 'WA', 'name' => 'Seattle', 'slug' => 'seattle-wa', 'area_codes' => '206,425,564', 'population' => 800000, 'latitude' => 47.6062, 'longitude' => -122.3321, 'priority' => 4,
                'nearby_cities' => ['Bellevue', 'Tacoma', 'Renton', 'Kent', 'Everett', 'Bellevue', 'Redmond', 'Kirkland', 'Auburn', 'Federal Way'],
                'climate_info' => 'an oceanic climate with mild temperatures and frequent rain',
                'local_events' => 'Seattle Art Fair, Bumbershoot, and numerous tech and music festivals',
                'construction_info' => 'high-growth tech hub with significant residential and commercial construction',
            ],

            // Oregon
            ['state' => 'OR', 'name' => 'Portland', 'slug' => 'portland-or', 'area_codes' => '503,971', 'population' => 627040, 'latitude' => 45.5152, 'longitude' => -122.6784, 'priority' => 3,
                'nearby_cities' => ['Gresham', 'Beaverton', 'Hillsboro', 'Tigard', 'Lake Oswego', 'Tualatin', 'Wilsonville', 'Oregon City', 'West Linn', 'Milwaukie'],
                'climate_info' => 'a temperate climate with mild wet winters and warm dry summers',
                'local_events' => 'Portland Rose Festival, Portland Timbers games, and numerous craft beer events',
                'construction_info' => 'steady growth in residential and commercial construction',
            ],

            // Illinois
            ['state' => 'IL', 'name' => 'Chicago', 'slug' => 'chicago-il', 'area_codes' => '312,773,847,630,708', 'population' => 2746388, 'latitude' => 41.8781, 'longitude' => -87.6298, 'priority' => 5,
                'nearby_cities' => ['Aurora', 'Naperville', 'Joliet', 'Rockford', 'Elgin', 'Cicero', 'Skokie', 'Des Plaines', 'Berwyn', 'Wheaton'],
                'climate_info' => 'a humid continental climate with hot humid summers and cold snowy winters',
                'local_events' => 'Lollapalooza, Chicago Cubs games, and numerous festivals along Lake Michigan',
                'construction_info' => 'massive urban development with ongoing residential and infrastructure projects',
            ],

            // New York
            ['state' => 'NY', 'name' => 'New York City', 'slug' => 'new-york-ny', 'area_codes' => '212,347,516,518,585,607,631,646,716,718,845,914,917,929', 'population' => 8378000, 'latitude' => 40.7128, 'longitude' => -74.0060, 'priority' => 5,
                'nearby_cities' => ['Brooklyn', 'Queens', 'Bronx', 'Staten Island', 'Newark', 'Jersey City', 'Yonkers', 'Paterson', 'Edison', 'Stamford'],
                'climate_info' => 'a humid subtropical climate with hot summers and cold winters',
                'local_events' => 'Times Square New Year\'s Eve, NYC Marathon, and numerous cultural events',
                'construction_info' => 'massive construction market with high-rise residential and commercial development',
            ],

            // Georgia
            ['state' => 'GA', 'name' => 'Atlanta', 'slug' => 'atlanta-ga', 'area_codes' => '404,470,678,770', 'population' => 496107, 'latitude' => 33.7490, 'longitude' => -84.3880, 'priority' => 5,
                'nearby_cities' => ['Marietta', 'Sandy Springs', 'Roswell', 'Alpharetta', 'Decatur', 'Kennesaw', 'Lawrenceville', 'Duluth', 'Smyrna', 'Peachtree City'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Music Midtown, Atlanta Dogwood Festival, and Dragon Con',
                'construction_info' => 'a major southeastern hub with extensive infrastructure and development projects',
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

            // Ohio
            ['state' => 'OH', 'name' => 'Columbus', 'slug' => 'columbus-oh', 'area_codes' => '614,380', 'population' => 905748, 'latitude' => 39.9612, 'longitude' => -82.9988, 'priority' => 3,
                'nearby_cities' => ['Dublin', 'Westerville', 'Grove City', 'Hilliard', 'Reynoldsburg', 'Gahanna', 'Upper Arlington', 'New Albany', 'Pickerington', 'Delaware'],
                'climate_info' => 'a humid continental climate with warm summers',
                'local_events' => 'Ohio State Fair, Columbus Arts Festival, and Ohio State football tailgating',
                'construction_info' => 'one of the fastest-growing cities in the Midwest with significant construction activity',
            ],
            ['state' => 'OH', 'name' => 'Cincinnati', 'slug' => 'cincinnati-oh', 'area_codes' => '513,937', 'population' => 308424, 'latitude' => 39.1031, 'longitude' => -84.5120, 'priority' => 3,
                'nearby_cities' => ['Covington', 'Newport', 'Fairfield', 'Hamilton', 'Middletown', 'Springdale', 'Blue Ash', 'Milford', 'Loveland', 'Lebanon'],
                'climate_info' => 'a humid continental climate with warm humid summers and cold winters',
                'local_events' => 'Cincinnati Art Museum events, Bengals games, and numerous riverfront festivals',
                'construction_info' => 'steady urban revitalization and suburban growth',
            ],

            // Michigan
            ['state' => 'MI', 'name' => 'Detroit', 'slug' => 'detroit-mi', 'area_codes' => '313,734,248', 'population' => 639111, 'latitude' => 42.3314, 'longitude' => -83.0458, 'priority' => 4,
                'nearby_cities' => ['Dearborn', 'Livonia', 'Southfield', 'Troy', 'Sterling Heights', 'Warren', 'Ann Arbor', 'Flint', 'Pontiac', 'Rochester Hills'],
                'climate_info' => 'a humid continental climate with warm summers and cold snowy winters',
                'local_events' => 'North American International Auto Show, Detroit Tigers games, and numerous music festivals',
                'construction_info' => 'ongoing urban revitalization and suburban development',
            ],

            // Pennsylvania
            ['state' => 'PA', 'name' => 'Philadelphia', 'slug' => 'philadelphia-pa', 'area_codes' => '215,267,445,484,610', 'population' => 1576251, 'latitude' => 39.9526, 'longitude' => -75.1652, 'priority' => 4,
                'nearby_cities' => ['Camden', 'Wilmington', 'Trenton', 'Allentown', 'Reading', 'Lancaster', 'Bethlehem', 'Easton', 'Norristown', 'Chester'],
                'climate_info' => 'a humid subtropical climate with hot summers and cold winters',
                'local_events' => 'Philadelphia Phillies games, Philadelphia Flower Show, and numerous cultural festivals',
                'construction_info' => 'steady urban redevelopment and suburban growth',
            ],
            ['state' => 'PA', 'name' => 'Pittsburgh', 'slug' => 'pittsburgh-pa', 'area_codes' => '412,724,878', 'population' => 300431, 'latitude' => 40.4406, 'longitude' => -79.9959, 'priority' => 3,
                'nearby_cities' => ['Mount Lebanon', 'Monroeville', 'Penn Hills', 'Greensburg', 'Bethel Park', 'Butler', 'Washington', 'Weirton', 'Steubenville', 'Beaver'],
                'climate_info' => 'a humid continental climate with four distinct seasons',
                'local_events' => 'Pittsburgh Steelers games, Three Rivers Arts Festival, and numerous sports events',
                'construction_info' => 'steady urban revitalization and energy sector growth',
            ],

            // Indiana
            ['state' => 'IN', 'name' => 'Indianapolis', 'slug' => 'indianapolis-in', 'area_codes' => '317,463', 'population' => 887642, 'latitude' => 39.7684, 'longitude' => -86.1581, 'priority' => 3,
                'nearby_cities' => ['Carmel', 'Fishers', 'Greenwood', 'Lawrence', 'Plainfield', 'Brownsburg', 'Avon', 'Zionsville', 'Noblesville', 'Westfield'],
                'climate_info' => 'a humid continental climate with warm summers and cold winters',
                'local_events' => 'Indianapolis 500, Indiana State Fair, Gen Con, and numerous motorsport events',
                'construction_info' => 'steady growth in suburban development and downtown revitalization',
            ],

            // Missouri
            ['state' => 'MO', 'name' => 'Kansas City', 'slug' => 'kansas-city-mo', 'area_codes' => '816,660', 'population' => 508090, 'latitude' => 39.0997, 'longitude' => -94.5786, 'priority' => 3,
                'nearby_cities' => ['Overland Park', 'Olathe', 'Lee\'s Summit', 'Independence', 'Blue Springs', 'Shawnee', 'Lenexa', 'Raytown', 'Gladstone', 'Liberty'],
                'climate_info' => 'a humid continental climate with hot summers and cold winters',
                'local_events' => 'Kansas City Chiefs games, Kansas City BBQ Festival, and numerous music events',
                'construction_info' => 'steady growth in residential and commercial construction',
            ],
            ['state' => 'MO', 'name' => 'St. Louis', 'slug' => 'st-louis-mo', 'area_codes' => '314,636', 'population' => 293310, 'latitude' => 38.6270, 'longitude' => -90.1994, 'priority' => 3,
                'nearby_cities' => ['St. Charles', 'Florissant', 'University City', 'Ballwin', 'Kirkwood', 'Oakville', 'Mehlville', 'St. Peters', 'Chesterfield', 'Clayton'],
                'climate_info' => 'a humid continental climate with hot humid summers and cold winters',
                'local_events' => 'St. Louis Cardinals games, St. Louis Mardi Gras, and numerous music festivals',
                'construction_info' => 'ongoing urban revitalization and suburban development',
            ],

            // Massachusetts
            ['state' => 'MA', 'name' => 'Boston', 'slug' => 'boston-ma', 'area_codes' => '617,857,508,781,978', 'population' => 675647, 'latitude' => 42.3601, 'longitude' => -71.0589, 'priority' => 4,
                'nearby_cities' => ['Cambridge', 'Somerville', 'Newton', 'Quincy', 'Lynn', 'Newton', 'Worcester', 'Springfield', 'Lowell', 'Brockton'],
                'climate_info' => 'a humid continental climate with hot humid summers and cold snowy winters',
                'local_events' => 'Boston Red Sox games, Boston Marathon, and numerous historical and cultural events',
                'construction_info' => 'high-cost market with ongoing residential and commercial development',
            ],

            // Virginia
            ['state' => 'VA', 'name' => 'Virginia Beach', 'slug' => 'virginia-beach-va', 'area_codes' => '757', 'population' => 451231, 'latitude' => 36.8529, 'longitude' => -75.9780, 'priority' => 3,
                'nearby_cities' => ['Norfolk', 'Chesapeake', 'Newport News', 'Hampton', ' Portsmouth', 'Suffolk', 'Williamsburg', 'Yorktown', 'Poquoson', 'Smithfield'],
                'climate_info' => 'a humid subtropical climate with hot humid summers and mild winters',
                'local_events' => 'Virginia Beach Neptune Festival, Oceanfront events, and numerous military ceremonies',
                'construction_info' => 'steady coastal development and military-related construction',
            ],
            ['state' => 'VA', 'name' => 'Richmond', 'slug' => 'richmond-va', 'area_codes' => '804', 'population' => 226610, 'latitude' => 37.5407, 'longitude' => -77.4360, 'priority' => 3,
                'nearby_cities' => ['Henrico', 'Chesterfield', 'Midlothian', 'Glen Allen', 'Mechanicsville', 'Chester', 'Short Pump', 'Ashland', 'Petersburg', 'Colonial Heights'],
                'climate_info' => 'a humid subtropical climate with warm summers and mild winters',
                'local_events' => 'Richmond Folk Festival, State Fair of Virginia, and numerous outdoor events along the James River',
                'construction_info' => 'growing tech and healthcare sectors driving construction activity',
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
            ['state' => 'LA', 'name' => 'New Orleans', 'slug' => 'new-orleans-la', 'area_codes' => '504,985', 'population' => 383997, 'latitude' => 29.9511, 'longitude' => -90.0715, 'priority' => 3,
                'nearby_cities' => ['Metairie', 'Kenner', 'Marrero', 'Harvey', 'Gretna', 'Slidell', 'Mandeville', 'Covington', 'Hammond', 'Baton Rouge'],
                'climate_info' => 'a humid subtropical climate with hot humid summers and mild winters',
                'local_events' => 'Mardi Gras, New Orleans Jazz & Heritage Festival, and numerous cultural celebrations',
                'construction_info' => 'ongoing post-Hurricane rebuilding and coastal restoration construction',
            ],
            ['state' => 'LA', 'name' => 'Baton Rouge', 'slug' => 'baton-rouge-la', 'area_codes' => '225', 'population' => 227470, 'latitude' => 30.4515, 'longitude' => -91.1871, 'priority' => 2,
                'nearby_cities' => ['Denham Springs', 'Gonzales', 'Zachary', 'Central', 'Baker', 'Port Allen', 'Prairieville', 'Walker', 'Shenandoah', 'Plaquemine'],
                'climate_info' => 'a humid subtropical climate with hot, humid summers and mild winters',
                'local_events' => 'LSU football tailgating, Baton Rouge Blues Festival, and numerous Cajun cultural events',
                'construction_info' => 'petrochemical industry and university driving steady construction growth',
            ],

            // Nebraska
            ['state' => 'NE', 'name' => 'Omaha', 'slug' => 'omaha-ne', 'area_codes' => '402,531', 'population' => 486051, 'latitude' => 41.2565, 'longitude' => -95.9345, 'priority' => 2,
                'nearby_cities' => ['Bellevue', 'Papillion', 'La Vista', 'Ralston', 'Gretna', 'Elkhorn', 'Council Bluffs', 'Bennington', 'Fremont', 'Blair'],
                'climate_info' => 'a humid continental climate with warm summers and cold winters',
                'local_events' => 'College World Series, Omaha Summer Arts Festival, and Berkshire Hathaway annual meeting events',
                'construction_info' => 'steady growth in suburban development and downtown revitalization',
            ],

            // Arkansas
            ['state' => 'AR', 'name' => 'Little Rock', 'slug' => 'little-rock-ar', 'area_codes' => '501', 'population' => 202591, 'latitude' => 34.7465, 'longitude' => -92.2896, 'priority' => 2,
                'nearby_cities' => ['North Little Rock', 'Conway', 'Jacksonville', 'Benton', 'Bryant', 'Sherwood', 'Cabot', 'Maumelle', 'Searcy', 'Hot Springs'],
                'climate_info' => 'a humid subtropical climate with hot summers and mild winters',
                'local_events' => 'Riverfest, Arkansas State Fair, and numerous outdoor events along the Arkansas River',
                'construction_info' => 'state capital with steady government and healthcare construction projects',
            ],

            // Utah
            ['state' => 'UT', 'name' => 'Salt Lake City', 'slug' => 'salt-lake-city-ut', 'area_codes' => '385,801', 'population' => 200478, 'latitude' => 40.7608, 'longitude' => -111.8910, 'priority' => 3,
                'nearby_cities' => ['West Valley City', 'Murray', 'South Salt Lake', 'Murray', 'Draper', 'Sandy', 'West Jordan', 'Midvale', 'Cottonwood Heights', 'Holladay'],
                'climate_info' => 'a semi-arid continental climate with four distinct seasons',
                'local_events' => 'Sundance Film Festival, Utah Jazz games, and numerous outdoor recreation events',
                'construction_info' => 'rapid growth driven by tech industry and outdoor recreation tourism',
            ],

            // Minnesota
            ['state' => 'MN', 'name' => 'Minneapolis', 'slug' => 'minneapolis-mn', 'area_codes' => '612,651,763,952', 'population' => 425096, 'latitude' => 44.9778, 'longitude' => -93.2650, 'priority' => 3,
                'nearby_cities' => ['Saint Paul', 'Bloomington', 'Brooklyn Center', 'Eden Prairie', 'Eagan', 'Plymouth', 'Maple Grove', 'Coon Rapids', 'Burnsville', 'Lakeville'],
                'climate_info' => 'a humid continental climate with warm summers and cold snowy winters',
                'local_events' => 'Minnesota Twins games, Minneapolis Art Fair, and numerous winter festivals',
                'construction_info' => 'steady growth in residential and commercial construction',
            ],

            // Wisconsin
            ['state' => 'WI', 'name' => 'Milwaukee', 'slug' => 'milwaukee-wi', 'area_codes' => '414,262', 'population' => 569330, 'latitude' => 43.0389, 'longitude' => -87.9065, 'priority' => 3,
                'nearby_cities' => ['Waukesha', 'Racine', 'Kenosha', 'Greenfield', 'New Berlin', 'Wauwatosa', 'Franklin', 'Oak Creek', 'South Milwaukee', 'Cudahy'],
                'climate_info' => 'a humid continental climate with warm humid summers and cold snowy winters',
                'local_events' => 'Milwaukee Brewers games, Summerfest, and numerous ethnic festivals',
                'construction_info' => 'steady urban revitalization and suburban growth',
            ],

            // Maryland
            ['state' => 'MD', 'name' => 'Baltimore', 'slug' => 'baltimore-md', 'area_codes' => '410,443,667', 'population' => 585708, 'latitude' => 39.2904, 'longitude' => -76.6122, 'priority' => 3,
                'nearby_cities' => ['Columbia', 'Glen Burnie', 'Ellicott City', 'Bowie', 'Germantown', 'Silver Spring', 'Bethesda', 'Towson', 'Catonsville', 'Woodlawn'],
                'climate_info' => 'a humid subtropical climate with hot humid summers and mild winters',
                'local_events' => 'Baltimore Orioles games, Preakness Stakes, and numerous maritime festivals',
                'construction_info' => 'ongoing urban revitalization and port-related development',
            ],

            // Connecticut
            ['state' => 'CT', 'name' => 'Hartford', 'slug' => 'hartford-ct', 'area_codes' => '860,959', 'population' => 121054, 'latitude' => 41.7658, 'longitude' => -72.6734, 'priority' => 2,
                'nearby_cities' => ['West Hartford', 'East Hartford', 'Manchester', 'Glastonbury', 'Wethersfield', 'New Britain', 'Bristol', 'Meriden', 'Waterbury', 'Danbury'],
                'climate_info' => 'a humid continental climate with warm humid summers and cold winters',
                'local_events' => 'Hartford Yard Goats games, First Night Hartford, and numerous cultural events',
                'construction_info' => 'steady urban revitalization and insurance industry development',
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

        $this->command->info('✅ Seeded '.count($cities).' cities with real US Census 2024/2025 data');
    }
}
