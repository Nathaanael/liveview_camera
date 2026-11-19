<?php

return [
    /*
    | Base URL of the go2rtc server as seen from the viewer's browser.
    | Laptop test: http://127.0.0.1:1984 — Server: http://10.126.27.68:1984
    */
    'go2rtc_url' => rtrim(env('GO2RTC_URL', 'http://127.0.0.1:1984'), '/'),

    // Player modes tried in order by go2rtc (webrtc, mse, hls, mjpeg).
    'player_mode' => env('GO2RTC_MODE', 'webrtc,mse'),

    /*
    | Test cameras (temporary — will move to the database with departments).
    | "stream" is the base name in go2rtc.yaml; the page uses "<stream>_sub"
    | for the grid and "<stream>_main" for fullscreen.
    */
    'test_cameras' => [
        ['name' => 'Kamera Uji 1', 'stream' => 'cam1'],
    ],
];
