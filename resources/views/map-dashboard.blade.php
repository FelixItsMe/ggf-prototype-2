<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('fontawesome-free-6.5.1-web/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('leaflet_1_9_4/leaflet.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/map.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/icon.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/leaflet-beautify-marker-icon.css') }}" />
</head>

<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">

        <div class="w-full sm:max-w-7xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="py-6">
                <div class="max-w-7xl sm:px-6 lg:px-8">
                    <div id="map"></div>
                    <i class="fa-solid fa-arrow-up text-7xl cog-arrow" id="first-arrow"></i>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('build/assets/app-kFrOrUDV.js') }}"></script>

<script src="{{ asset('js/telemetri.js') }}"></script>
<script src="{{ asset('leaflet_1_9_4/leaflet.js') }}"></script>
<script src="{{ asset('js/MovingMarker.js') }}"></script>
<script src="{{ asset('js/leaflet-beautify-marker-icon.js') }}"></script>
<script>
    let intervalSpeed = 400
    let movingMarker = null

    const createMovingMarker = (map, latitude, longitude, intervalSpeed = 400) => {
        return L.Marker.movingMarker([
                [latitude, longitude],
                [latitude, longitude]
            ],
            [intervalSpeed]).setIcon(L.BeautifyIcon.icon({
                    icon: 'arrow-up',
                    borderColor: '#0006ff',
                    textColor: '#0006ff',
                    // backgroundColor: '#fff',
                    iconSize: [25, 25],
                    iconShape: 'circle',
                    innerIconAnchor: [0, 5],
                    innerIconStyle: 'transform:rotate(90deg);transition: .7s ease-in-out transform;'
                })).addTo(map);
    }

    const renderNewData = async (elementID, telemetries = []) => {
        let eTelemetries = ``
        telemetries.forEach(telemetri => {
            eTelemetries += `<tr>
                                    <td class="px-4 py-2 border border-gray-300">${telemetri.id}</td>
                                    <td class="px-4 py-2 border border-gray-300">${telemetri.device_timestamp}</td>
                                    <td class="px-4 py-2 border border-gray-300">${telemetri.created_at}</td>
                                    <td class="px-4 py-2 border border-gray-300">${telemetri.raw_data.latitude}</td>
                                    <td class="px-4 py-2 border border-gray-300">${telemetri.raw_data.longitude}</td>
                                    <td class="px-4 py-2 border border-gray-300">${telemetri.raw_data.sog}</td>
                                    <td class="px-4 py-2 border border-gray-300">${telemetri.raw_data.cog}</td>
                                </tr>`
        })

        document.getElementById(elementID).innerHTML = eTelemetries
    }

    const renderMissingData = async (elementID, telemetries = []) => {
        let eTelemetries = ``
        telemetries.forEach(telemetri => {
            eTelemetries += `<tr>
                                    <td class="px-4 py-2 bg-red-200 border border-gray-300">${telemetri}</td>
                                </tr>`
        })

        document.getElementById(elementID).innerHTML = eTelemetries
    }

    const rotateElement = (element, cog = 0) => {
        element.style.webkitTransform = `rotate(${cog}deg)`;
        element.style.mozTransform = `rotate(${cog}deg)`;
        element.style.msTransform = `rotate(${cog}deg)`;
        element.style.oTransform = `rotate(${cog}deg)`;
        element.style.transform = `rotate(${cog}deg)`;
    }

    Echo.channel('data-sensor')
        .listen('NewDataSensor', (e) => {
            console.log(e.data);
            // console.dir(movingMarker);

            rotateElement(movingMarker._icon.children[0], e.data)
        })

    window.onload = async () => {
        let coordinate = []
        let polyline = null
        let map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        movingMarker = createMovingMarker(map, -6.963802, 107.689119)

        map.setView([-6.963802, 107.689119], 13)
    }
</script>

</html>
