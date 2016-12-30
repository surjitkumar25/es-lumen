<?php namespace App\Console\Commands;

use App\Edition;
use App\Picture;
use App\Story;
use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class ImportCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sync-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports data from mysql to elastic search';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $hosts = [
            'http://elastic:qM4jpXG1wCPAYRlWhiDE1zau@4672587a369969e3f0f9b0b81d39e790.us-east-1.aws.found.io:9200',
        ];

        $index = [
            'index' => 'estest',
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    'story' => [
                        'properties' => [
                            "EditionDate"  => [
                                "type" => "string"
                            ],
                            "EditionDisplayName" => [
                                "type" => "string"
                            ],
                            "EditionID" => [
                                "type" => "long"
                            ],
                            "EditionLocation" => [
                                "type" => "string"
                            ],
                            "PageFileName" => [
                                "type" => "string"
                            ],
                            "StoryFileName" => [
                                "type" => "string"
                            ],
                            "StoryTitle" => [
                                "type" => "string"
                            ],
                            "body" => [
                                "type" => "string"
                            ],
                            "pageNum" => [
                                "type" => "string"
                            ],
                            "picture" => [
                                "properties" => [
                                    "captions" => [
                                        "type" => "string"
                                    ],
                                    "pictureFileName" => [
                                        "type" => "string"
                                    ],
                                    "pictureid" => [
                                        "type" => "long"
                                    ]
                                ]
                            ],
                            "storyid" => [
                                "type" => "long"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->info("Initiating connection with elastic search.");
        try {
            $esClient = ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
            $this->info('Connection established successfully.');
            $exists = $esClient->indices()->exists(['index' => 'estest']);
            if (!$exists) {
                $this->info('Creating index ...');
                $esClient->indices()->create($index);
                $this->info('Index created successfully.');
            }
            $stories = Story::all();
            $bar = $this->output->createProgressBar($stories->count());
            foreach ($stories as $story) {
                $params = [
                    'index' => 'estest',
                    'type' => 'story',
                    'id' => $story->StoryID
                ];

                $params['body'] = [
                    'storyid' => $story->StoryID,
                    'EditionLocation' => $story->edition->EditionLocation,
                    'EditionDisplayName' => $story->edition->EditionDisplayName,
                    'EditionID' => $story->edition->EditionID,
                    'date' => $story->Dateline,
                    'pageNum' => $story->page->PageNumber,
                    'PageTitle' => $story->page->NewsProPageTitle,
                    'PageFileName' => $story->page->FileName,
                    'StoryTitle' => $story->StoryTitle,
                    'EditionDate' => $story->EditionDate,
                    'StoryFileName' => $story->FileName,
                    'body' => $story->FTSText,
                    'picture' => $this->getPicturesArray($story->page->pictures)
                ];
                $esClient->index($params);
                $bar->advance();
            }
            $bar->finish();
            $this->info('Data synced successfully.');
        } catch (\Exception $exception) {
            $this->error($exception->getLine());
            $this->error($exception->getMessage());
        }
    }

    private function getPicturesArray($pictures)
    {
        return array_map(function($picture) {
            return [
                'pictureid' => $picture['PictureID'],
                'captions' => $picture['Caption'],
                'pictureFileName' => $picture['FileName']
            ];
        }, $pictures->toArray());
    }
}
