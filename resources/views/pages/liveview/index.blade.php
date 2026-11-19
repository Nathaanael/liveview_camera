@extends('layouts.app')

@section('content')
    <div>
        <x-common.page-breadcrumb pageTitle="{{ __('Live View') }}" />
        <x-main.liveview.camera-grid :cameras="$cameras" :go2rtc-url="$go2rtcUrl" :player-mode="$playerMode" />
    </div>
@endsection
