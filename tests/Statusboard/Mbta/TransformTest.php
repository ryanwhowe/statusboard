<?php

namespace Tests\Statusboard\Mbta;

use Statusboard\Mbta\Transform;
use PHPUnit\Framework\TestCase;

class TransformTest extends TestCase {

    public function getScheduleData(){
        $data = '
        {
    "data": [
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T04:15:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-701-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-701",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T04:37:00-05:00",
                "departure_time": "2020-03-06T04:37:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": false
            },
            "id": "schedule-CR-Weekday-Fall-19-701-Norwood Central-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-701",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T04:43:00-05:00",
                "departure_time": "2020-03-06T04:43:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": false
            },
            "id": "schedule-CR-Weekday-Fall-19-701-Walpole-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-701",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T04:55:00-05:00",
                "departure_time": "2020-03-06T04:55:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-701-Franklin-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-701",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T05:03:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-701-Forge Park \/ 495-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-701",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T08:04:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:09:00-05:00",
                "departure_time": "2020-03-06T08:09:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:13:00-05:00",
                "departure_time": "2020-03-06T08:13:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:23:00-05:00",
                "departure_time": "2020-03-06T08:23:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Readville-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:26:00-05:00",
                "departure_time": "2020-03-06T08:26:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Endicott-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:29:00-05:00",
                "departure_time": "2020-03-06T08:29:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Dedham Corp Center-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:32:00-05:00",
                "departure_time": "2020-03-06T08:32:00-05:00",
                "direction_id": 0,
                "drop_off_type": 3,
                "pickup_type": 3,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Islington-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:35:00-05:00",
                "departure_time": "2020-03-06T08:35:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Norwood Depot-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:38:00-05:00",
                "departure_time": "2020-03-06T08:38:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Norwood Central-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:42:00-05:00",
                "departure_time": "2020-03-06T08:42:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Windsor Gardens-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:46:00-05:00",
                "departure_time": "2020-03-06T08:46:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Walpole-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:52:00-05:00",
                "departure_time": "2020-03-06T08:52:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Norfolk-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T08:59:00-05:00",
                "departure_time": "2020-03-06T08:59:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Franklin-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T09:07:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 14,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-703-Forge Park \/ 495-14",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-703",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T09:40:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T09:45:00-05:00",
                "departure_time": "2020-03-06T09:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T09:56:00-05:00",
                "departure_time": "2020-03-06T09:56:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Readville-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T09:59:00-05:00",
                "departure_time": "2020-03-06T09:59:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Endicott-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:02:00-05:00",
                "departure_time": "2020-03-06T10:02:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Dedham Corp Center-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:05:00-05:00",
                "departure_time": "2020-03-06T10:05:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Islington-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:08:00-05:00",
                "departure_time": "2020-03-06T10:08:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Norwood Depot-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:11:00-05:00",
                "departure_time": "2020-03-06T10:11:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Norwood Central-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:15:00-05:00",
                "departure_time": "2020-03-06T10:15:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Windsor Gardens-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:19:00-05:00",
                "departure_time": "2020-03-06T10:19:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Walpole-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:25:00-05:00",
                "departure_time": "2020-03-06T10:25:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Norfolk-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:32:00-05:00",
                "departure_time": "2020-03-06T10:32:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Franklin-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T10:40:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-705-Forge Park \/ 495-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-705",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T11:00:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:05:00-05:00",
                "departure_time": "2020-03-06T11:05:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:08:00-05:00",
                "departure_time": "2020-03-06T11:08:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:19:00-05:00",
                "departure_time": "2020-03-06T11:19:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Endicott-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:22:00-05:00",
                "departure_time": "2020-03-06T11:22:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Dedham Corp Center-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:25:00-05:00",
                "departure_time": "2020-03-06T11:25:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Islington-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:28:00-05:00",
                "departure_time": "2020-03-06T11:28:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Norwood Depot-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:31:00-05:00",
                "departure_time": "2020-03-06T11:31:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Norwood Central-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:35:00-05:00",
                "departure_time": "2020-03-06T11:35:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Windsor Gardens-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:39:00-05:00",
                "departure_time": "2020-03-06T11:39:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Walpole-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:45:00-05:00",
                "departure_time": "2020-03-06T11:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Norfolk-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T11:52:00-05:00",
                "departure_time": "2020-03-06T11:52:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Franklin-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:00:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-707-Forge Park \/ 495-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-707",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T12:20:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:25:00-05:00",
                "departure_time": "2020-03-06T12:25:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:28:00-05:00",
                "departure_time": "2020-03-06T12:28:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:38:00-05:00",
                "departure_time": "2020-03-06T12:38:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Readville-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:41:00-05:00",
                "departure_time": "2020-03-06T12:41:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Endicott-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:44:00-05:00",
                "departure_time": "2020-03-06T12:44:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Dedham Corp Center-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:47:00-05:00",
                "departure_time": "2020-03-06T12:47:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Islington-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:50:00-05:00",
                "departure_time": "2020-03-06T12:50:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Norwood Depot-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:53:00-05:00",
                "departure_time": "2020-03-06T12:53:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Norwood Central-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T12:57:00-05:00",
                "departure_time": "2020-03-06T12:57:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Windsor Gardens-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:01:00-05:00",
                "departure_time": "2020-03-06T13:01:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Walpole-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:07:00-05:00",
                "departure_time": "2020-03-06T13:07:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Norfolk-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:14:00-05:00",
                "departure_time": "2020-03-06T13:14:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Franklin-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:22:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 14,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-709-Forge Park \/ 495-14",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-709",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T13:35:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:40:00-05:00",
                "departure_time": "2020-03-06T13:40:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:44:00-05:00",
                "departure_time": "2020-03-06T13:44:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:53:00-05:00",
                "departure_time": "2020-03-06T13:53:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Readville-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:57:00-05:00",
                "departure_time": "2020-03-06T13:57:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Endicott-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T13:59:00-05:00",
                "departure_time": "2020-03-06T13:59:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Dedham Corp Center-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:02:00-05:00",
                "departure_time": "2020-03-06T14:02:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Islington-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:05:00-05:00",
                "departure_time": "2020-03-06T14:05:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Norwood Depot-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:08:00-05:00",
                "departure_time": "2020-03-06T14:08:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Norwood Central-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:12:00-05:00",
                "departure_time": "2020-03-06T14:12:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Windsor Gardens-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:16:00-05:00",
                "departure_time": "2020-03-06T14:16:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Walpole-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:22:00-05:00",
                "departure_time": "2020-03-06T14:22:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Norfolk-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:29:00-05:00",
                "departure_time": "2020-03-06T14:29:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Franklin-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:37:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 14,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-711-Forge Park \/ 495-14",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-711",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T14:40:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:45:00-05:00",
                "departure_time": "2020-03-06T14:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:49:00-05:00",
                "departure_time": "2020-03-06T14:49:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T14:59:00-05:00",
                "departure_time": "2020-03-06T14:59:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Readville-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T15:04:00-05:00",
                "departure_time": "2020-03-06T15:04:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Dedham Corp Center-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T15:10:00-05:00",
                "departure_time": "2020-03-06T15:10:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Norwood Central-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T15:18:00-05:00",
                "departure_time": "2020-03-06T15:18:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Walpole-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T15:25:00-05:00",
                "departure_time": "2020-03-06T15:25:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Norfolk-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T15:32:00-05:00",
                "departure_time": "2020-03-06T15:32:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Franklin-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T15:40:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-713-Forge Park \/ 495-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-713",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T15:40:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T15:45:00-05:00",
                "departure_time": "2020-03-06T15:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T15:49:00-05:00",
                "departure_time": "2020-03-06T15:49:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T16:04:00-05:00",
                "departure_time": "2020-03-06T16:04:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-Dedham Corp Center-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T16:10:00-05:00",
                "departure_time": "2020-03-06T16:10:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-Norwood Central-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T16:19:00-05:00",
                "departure_time": "2020-03-06T16:19:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-Walpole-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T16:26:00-05:00",
                "departure_time": "2020-03-06T16:26:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-Norfolk-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T16:33:00-05:00",
                "departure_time": "2020-03-06T16:33:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-Franklin-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T16:41:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-715-Forge Park \/ 495-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-715",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T16:40:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T16:45:00-05:00",
                "departure_time": "2020-03-06T16:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T16:49:00-05:00",
                "departure_time": "2020-03-06T16:49:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:04:00-05:00",
                "departure_time": "2020-03-06T17:04:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-Dedham Corp Center-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:10:00-05:00",
                "departure_time": "2020-03-06T17:10:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-Norwood Central-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:19:00-05:00",
                "departure_time": "2020-03-06T17:19:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-Walpole-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:26:00-05:00",
                "departure_time": "2020-03-06T17:26:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-Norfolk-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:33:00-05:00",
                "departure_time": "2020-03-06T17:33:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-Franklin-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:42:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-717-Forge Park \/ 495-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-717",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T17:20:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:25:00-05:00",
                "departure_time": "2020-03-06T17:25:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:29:00-05:00",
                "departure_time": "2020-03-06T17:29:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:46:00-05:00",
                "departure_time": "2020-03-06T17:46:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Dedham Corp Center-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:49:00-05:00",
                "departure_time": "2020-03-06T17:49:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Islington-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:54:00-05:00",
                "departure_time": "2020-03-06T17:54:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Norwood Central-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:58:00-05:00",
                "departure_time": "2020-03-06T17:58:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Windsor Gardens-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:04:00-05:00",
                "departure_time": "2020-03-06T18:04:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Walpole-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:12:00-05:00",
                "departure_time": "2020-03-06T18:12:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Norfolk-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:19:00-05:00",
                "departure_time": "2020-03-06T18:19:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Franklin-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:29:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-719-Forge Park \/ 495-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-719",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T17:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:50:00-05:00",
                "departure_time": "2020-03-06T17:50:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T17:54:00-05:00",
                "departure_time": "2020-03-06T17:54:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:08:00-05:00",
                "departure_time": "2020-03-06T18:08:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Endicott-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:11:00-05:00",
                "departure_time": "2020-03-06T18:11:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Dedham Corp Center-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:18:00-05:00",
                "departure_time": "2020-03-06T18:18:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Norwood Depot-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:22:00-05:00",
                "departure_time": "2020-03-06T18:22:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Norwood Central-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:26:00-05:00",
                "departure_time": "2020-03-06T18:26:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Windsor Gardens-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:31:00-05:00",
                "departure_time": "2020-03-06T18:31:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Walpole-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:38:00-05:00",
                "departure_time": "2020-03-06T18:38:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Norfolk-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:45:00-05:00",
                "departure_time": "2020-03-06T18:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Franklin-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:54:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-721-Forge Park \/ 495-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-721",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T18:20:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:25:00-05:00",
                "departure_time": "2020-03-06T18:25:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:29:00-05:00",
                "departure_time": "2020-03-06T18:29:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:38:00-05:00",
                "departure_time": "2020-03-06T18:38:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Readville-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:42:00-05:00",
                "departure_time": "2020-03-06T18:42:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Endicott-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:45:00-05:00",
                "departure_time": "2020-03-06T18:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Dedham Corp Center-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:48:00-05:00",
                "departure_time": "2020-03-06T18:48:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Islington-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:52:00-05:00",
                "departure_time": "2020-03-06T18:52:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Norwood Depot-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T18:56:00-05:00",
                "departure_time": "2020-03-06T18:56:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Norwood Central-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T19:00:00-05:00",
                "departure_time": "2020-03-06T19:00:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Windsor Gardens-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T19:05:00-05:00",
                "departure_time": "2020-03-06T19:05:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Walpole-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T19:12:00-05:00",
                "departure_time": "2020-03-06T19:12:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Norfolk-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T19:19:00-05:00",
                "departure_time": "2020-03-06T19:19:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Franklin-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T19:30:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 14,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-723-Forge Park \/ 495-14",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-723",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T19:50:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T19:55:00-05:00",
                "departure_time": "2020-03-06T19:55:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T19:59:00-05:00",
                "departure_time": "2020-03-06T19:59:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:08:00-05:00",
                "departure_time": "2020-03-06T20:08:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Readville-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:11:00-05:00",
                "departure_time": "2020-03-06T20:11:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Endicott-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:14:00-05:00",
                "departure_time": "2020-03-06T20:14:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Dedham Corp Center-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:17:00-05:00",
                "departure_time": "2020-03-06T20:17:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Islington-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:20:00-05:00",
                "departure_time": "2020-03-06T20:20:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Norwood Depot-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:23:00-05:00",
                "departure_time": "2020-03-06T20:23:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Norwood Central-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:27:00-05:00",
                "departure_time": "2020-03-06T20:27:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Windsor Gardens-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:32:00-05:00",
                "departure_time": "2020-03-06T20:32:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Walpole-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:39:00-05:00",
                "departure_time": "2020-03-06T20:39:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Norfolk-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:46:00-05:00",
                "departure_time": "2020-03-06T20:46:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Franklin-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T20:54:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 14,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-725-Forge Park \/ 495-14",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-725",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T21:10:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:15:00-05:00",
                "departure_time": "2020-03-06T21:15:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:18:00-05:00",
                "departure_time": "2020-03-06T21:18:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:28:00-05:00",
                "departure_time": "2020-03-06T21:28:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Readville-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:31:00-05:00",
                "departure_time": "2020-03-06T21:31:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Endicott-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:34:00-05:00",
                "departure_time": "2020-03-06T21:34:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Dedham Corp Center-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:37:00-05:00",
                "departure_time": "2020-03-06T21:37:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Islington-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:40:00-05:00",
                "departure_time": "2020-03-06T21:40:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Norwood Depot-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:43:00-05:00",
                "departure_time": "2020-03-06T21:43:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Norwood Central-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:47:00-05:00",
                "departure_time": "2020-03-06T21:47:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Windsor Gardens-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:52:00-05:00",
                "departure_time": "2020-03-06T21:52:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Walpole-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T21:59:00-05:00",
                "departure_time": "2020-03-06T21:59:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Norfolk-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:06:00-05:00",
                "departure_time": "2020-03-06T22:06:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Franklin-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:14:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 14,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-727-Forge Park \/ 495-14",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-727",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T22:30:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:35:00-05:00",
                "departure_time": "2020-03-06T22:35:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:38:00-05:00",
                "departure_time": "2020-03-06T22:38:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:46:00-05:00",
                "departure_time": "2020-03-06T22:46:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Hyde Park-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Hyde Park",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:50:00-05:00",
                "departure_time": "2020-03-06T22:50:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Readville-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:53:00-05:00",
                "departure_time": "2020-03-06T22:53:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Endicott-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:56:00-05:00",
                "departure_time": "2020-03-06T22:56:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Dedham Corp Center-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T22:59:00-05:00",
                "departure_time": "2020-03-06T22:59:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Islington-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:02:00-05:00",
                "departure_time": "2020-03-06T23:02:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Norwood Depot-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:05:00-05:00",
                "departure_time": "2020-03-06T23:05:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Norwood Central-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:08:00-05:00",
                "departure_time": "2020-03-06T23:08:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Windsor Gardens-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:12:00-05:00",
                "departure_time": "2020-03-06T23:12:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Walpole-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:19:00-05:00",
                "departure_time": "2020-03-06T23:19:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Norfolk-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:26:00-05:00",
                "departure_time": "2020-03-06T23:26:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 14,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Franklin-14",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:34:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 15,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-729-Forge Park \/ 495-15",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-729",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": null,
                "departure_time": "2020-03-06T23:50:00-05:00",
                "direction_id": 0,
                "drop_off_type": 1,
                "pickup_type": 0,
                "stop_sequence": 1,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-South Station-1",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "South Station",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:55:00-05:00",
                "departure_time": "2020-03-06T23:55:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 2,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Back Bay-2",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Back Bay",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-06T23:58:00-05:00",
                "departure_time": "2020-03-06T23:58:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 3,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Ruggles-3",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Ruggles",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:08:00-05:00",
                "departure_time": "2020-03-07T00:08:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 4,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Readville-4",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Readville",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:11:00-05:00",
                "departure_time": "2020-03-07T00:11:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 5,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Endicott-5",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Endicott",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:14:00-05:00",
                "departure_time": "2020-03-07T00:14:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 6,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Dedham Corp Center-6",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Dedham Corp Center",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:17:00-05:00",
                "departure_time": "2020-03-07T00:17:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 7,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Islington-7",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Islington",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:20:00-05:00",
                "departure_time": "2020-03-07T00:20:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 8,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Norwood Depot-8",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Depot",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:23:00-05:00",
                "departure_time": "2020-03-07T00:23:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 9,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Norwood Central-9",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norwood Central",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:26:00-05:00",
                "departure_time": "2020-03-07T00:26:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 10,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Windsor Gardens-10",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Windsor Gardens",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:31:00-05:00",
                "departure_time": "2020-03-07T00:31:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 11,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Walpole-11",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Walpole",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:38:00-05:00",
                "departure_time": "2020-03-07T00:38:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 12,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Norfolk-12",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Norfolk",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:45:00-05:00",
                "departure_time": "2020-03-07T00:45:00-05:00",
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 0,
                "stop_sequence": 13,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Franklin-13",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Franklin",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        },
        {
            "attributes": {
                "arrival_time": "2020-03-07T00:53:00-05:00",
                "departure_time": null,
                "direction_id": 0,
                "drop_off_type": 0,
                "pickup_type": 1,
                "stop_sequence": 14,
                "timepoint": true
            },
            "id": "schedule-CR-Weekday-Fall-19-731-Forge Park \/ 495-14",
            "relationships": {
                "prediction": [],
                "route": {
                    "data": {
                        "id": "CR-Franklin",
                        "type": "route"
                    }
                },
                "stop": {
                    "data": {
                        "id": "Forge Park \/ 495",
                        "type": "stop"
                    }
                },
                "trip": {
                    "data": {
                        "id": "CR-Weekday-Fall-19-731",
                        "type": "trip"
                    }
                }
            },
            "type": "schedule"
        }
    ],
    "jsonapi": {
        "version": "1.0"
    }
}
        ';
        return json_decode($data,
            true);
    }

    /**
     * @test
     */
    public function responseProcessor() {
        $response = Transform::responseProcessor($this->getScheduleData());
        $this->assertArrayHasKey('expires', $response);
        $this->assertArrayHasKey('trips', $response);
    }

    /**
     * @test
     */
    public function filterTrips() {
        $filter_time = 1583519246; // this is an appropriate epoch time for when the test data was delivered 2020-03-06 @ 1:27:26 PM
        $stops = Transform::filterStops($this->getScheduleData(),[Transform::STATION_FILTER_SOUTHSTATION, Transform::STATION_FILTER_FORGEPARK]);
        $trips = Transform::parseTrips($stops);
        list($expires, $filtered_trips) = Transform::filterTrips($trips,1583519246);
        $next_train_time = 1583519700;
        $this->assertEquals($next_train_time, $expires, 'Expiration time should be next train departure time');

        $this->assertEquals(711, $filtered_trips[0]['trip']);
        $this->assertEquals(713, $filtered_trips[1]['trip']);
        $this->assertEquals(715, $filtered_trips[2]['trip']);

        $this->assertEquals($next_train_time, $filtered_trips[0]['departs']);
        $this->assertEquals(1583523600, $filtered_trips[1]['departs']);
        $this->assertEquals(1583527200, $filtered_trips[2]['departs']);

        $this->assertEquals(1583523420, $filtered_trips[0]['arrives']);
        $this->assertEquals(1583527200, $filtered_trips[1]['arrives']);
        $this->assertEquals(1583530860, $filtered_trips[2]['arrives']);
    }

    /**
     * @test
     */
    public function filterStops() {
        $filtered = Transform::filterStops($this->getScheduleData(),[Transform::STATION_FILTER_SOUTHSTATION]);
        $this->assertEquals(16, count($filtered));
        $this->assertArrayHasKey(0, $filtered);
        $this->assertArrayHasKey(19, $filtered);
        $this->assertArrayHasKey(45, $filtered);
        $this->assertArrayHasKey(73, $filtered);
        $this->assertArrayHasKey(92, $filtered);
        $this->assertArrayHasKey(112, $filtered);
        $this->assertArrayHasKey(138, $filtered);
        $this->assertArrayHasKey(166, $filtered);
    }

    /**
     * @test
     */
    public function parseTrips() {
        $stops = Transform::filterStops($this->getScheduleData(),[Transform::STATION_FILTER_SOUTHSTATION, Transform::STATION_FILTER_FORGEPARK]);
        $trips = Transform::parseTrips($stops);
        $this->assertEquals(16, count($trips));
    }
}
