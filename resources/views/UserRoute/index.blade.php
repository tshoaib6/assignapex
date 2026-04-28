@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<style>
  .route-map-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .route-card {
      border: none;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      border-radius: 12px;
      overflow: hidden;
  }
  .route-map {
    width: 100%;
    height: 75vh;
    z-index: 1;
  }
  .route-controls-overlay {
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 1000;
      background: rgba(255, 255, 255, 0.95);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      width: 320px;
      backdrop-filter: blur(5px);
      max-height: 90%;
      overflow-y: auto;
  }
  .route-stats {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
  }
  .stat-item {
      text-align: center;
  }
  .stat-value {
      font-size: 1.2rem;
      font-weight: bold;
      color: #2c3e50;
  }
  .stat-label {
      font-size: 0.8rem;
      color: #7f8c8d;
      text-transform: uppercase;
      letter-spacing: 0.5px;
  }
  .route-btn {
    width: 100%;
    margin-bottom: 10px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .route-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  .search-box-container {
      margin-bottom: 15px;
      position: relative;
  }
  .route-search-box {
      width: 100%;
      padding: 12px 15px;
      padding-left: 40px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      background: #f8f9fa;
      transition: border-color 0.2s;
  }
  .route-search-box:focus {
      outline: none;
      border-color: #3498db;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
  }
  .search-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #95a5a6;
  }

  /* Hide default routing container to use our custom UI */
  .leaflet-routing-container {
      display: none !important;
  }

  .btn-group-custom {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
  }
  .btn-group-custom .btn {
      flex: 1;
  }
</style>
@endpush

<div class="route-map-container">
    <div class="card route-card">
        <div class="card-body p-0 position-relative">
            <div id="routeMap" class="route-map"></div>

            <div class="route-controls-overlay">
                <h5 class="mb-3 fw-bold text-dark"><i class="fa fa-map-marked-alt me-2 text-primary"></i>Route Planner</h5>

                <div class="search-box-container">
                    <i class="fa fa-search search-icon"></i>
                    <input type="text" id="searchBox" class="route-search-box" placeholder="Search location...">
                </div>

                <div class="route-stats">
                    <div class="stat-item">
                        <div class="stat-value" id="distance">0</div>
                        <div class="stat-label">km</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="duration">0</div>
                        <div class="stat-label">min</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="stops-count">0</div>
                        <div class="stat-label">Stops</div>
                    </div>
                </div>

                <div class="btn-group-custom">
                    <button type="button" class="btn btn-outline-danger btn-sm route-btn mb-0" onclick="removeLastWaypoint()">
                        <i class="fa fa-undo me-1"></i> Undo Stop
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm route-btn mb-0" onclick="clearRoute()">
                        <i class="fa fa-trash me-1"></i> Clear All
                    </button>
                </div>

                <hr class="my-3">

                <button type="button" class="btn btn-primary route-btn" onclick="getRouteLink()">
                    <i class="fa fa-link me-2"></i> Copy Route Link
                </button>
                <button type="button" class="btn btn-success route-btn" onclick="downloadKML()">
                    <i class="fa fa-file-download me-2"></i> Download KML
                </button>
                <button type="button" class="btn btn-info route-btn text-white" onclick="downloadKMZ()">
                    <i class="fa fa-file-archive me-2"></i> Download KMZ
                </button>

                <div class="mt-3 text-muted small text-center bg-light p-2 rounded">
                    <i class="fa fa-mouse-pointer me-1"></i> Click on map to add stops.<br>
                    <i class="fa fa-hand-pointer me-1"></i> Drag markers to adjust route.
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/togeojson/0.16.0/togeojson.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const mapContainer = document.getElementById('routeMap');
    if (!mapContainer) return;

    // Default view: Riyadh, Saudi Arabia
    const map = L.map('routeMap').setView([24.7136, 46.6753], 12);
    window.map = map;

    // Use a nice tile layer (CartoDB Voyager is clean and modern)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Initialize Routing Control
    const control = L.Routing.control({
        waypoints: [],
        routeWhileDragging: true,
        geocoder: L.Control.Geocoder.nominatim(),
        show: false, // Hide default panel
        addWaypoints: false, // We handle adding via click
        createMarker: function(i, wp, nWps) {
            let iconUrl, color;

            if (i === 0) {
                // Start - Green
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png';
            } else if (i === nWps - 1) {
                // End - Red
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png';
            } else {
                // Intermediate - Blue
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png';
            }

            const icon = new L.Icon({
                iconUrl: iconUrl,
                shadowUrl: 'https://unpkg.com/leaflet/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const label = i === 0 ? 'Start' : (i === nWps - 1 ? 'End' : `Stop ${i}`);

            return L.marker(wp.latLng, {
                icon: icon,
                draggable: true
            }).bindTooltip(label, { permanent: false, direction: 'top' });
        }
    }).addTo(map);

    // Update stats when route is calculated
    control.on('routesfound', function(e) {
        const route = e.routes[0];
        const distKm = (route.summary.totalDistance / 1000).toFixed(2);
        const timeMin = Math.round(route.summary.totalTime / 60);

        document.getElementById('distance').innerText = distKm;
        document.getElementById('duration').innerText = timeMin;

        // Count stops (waypoints - start - end)
        // Actually user wants to know total points or stops.
        // Let's show total waypoints count.
        const wpCount = control.getWaypoints().filter(w => w.latLng).length;
        document.getElementById('stops-count').innerText = wpCount > 0 ? wpCount : 0;
    });

    // Click handler to add waypoints
    map.on('click', function(e) {
        const waypoints = control.getWaypoints();
        // Filter out any null/empty waypoints that might exist
        const cleanWaypoints = waypoints.filter(w => w.latLng);

        // Add new waypoint
        cleanWaypoints.push({ latLng: e.latlng });

        control.setWaypoints(cleanWaypoints);
    });

    // --- Global Functions ---

    window.removeLastWaypoint = function() {
        const waypoints = control.getWaypoints();
        const cleanWaypoints = waypoints.filter(w => w.latLng);

        if (cleanWaypoints.length > 0) {
            cleanWaypoints.pop(); // Remove last
            control.setWaypoints(cleanWaypoints);

            if (cleanWaypoints.length === 0) {
                resetStats();
            }
        }
    };

    window.clearRoute = function() {
        if(confirm('Are you sure you want to clear the entire route?')) {
            control.setWaypoints([]);
            resetStats();
        }
    };

    function resetStats() {
        document.getElementById('distance').innerText = '0';
        document.getElementById('duration').innerText = '0';
        document.getElementById('stops-count').innerText = '0';
    }

    window.generateKML = function() {
        if (!control._routes || !control._routes[0]) return null;
        const coords = control._routes[0].coordinates.map(c => c.lng + ',' + c.lat + ',0').join(" ");
        // Break the PHP tag to avoid parsing error
        const kmlContent = '<' + '?xml version="1.0" encoding="UTF-8"?' + '>' +
            '<kml xmlns="http://www.opengis.net/kml/2.2">' +
            '  <Document>' +
            '    <name>Route</name>' +
            '    <Placemark>' +
            '      <name>Route Path</name>' +
            '      <LineString>' +
            '        <tessellate>1</tessellate>' +
            '        <coordinates>' + coords + '</coordinates>' +
            '      </LineString>' +
            '    </Placemark>' +
            '  </Document>' +
            '</kml>';
        return kmlContent;
    };

    window.downloadKML = function() {
        const kml = window.generateKML();
        if (!kml) {
            alert("Please create a route first!");
            return;
        }
        const blob = new Blob([kml], { type: 'application/vnd.google-earth.kml+xml' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'route.kml';
        link.click();
    };

    window.downloadKMZ = function() {
        const kml = window.generateKML();
        if (!kml) {
            alert("Please create a route first!");
            return;
        }
        const zip = new JSZip();
        zip.file("route.kml", kml);
        zip.generateAsync({ type: "blob" }).then(content => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(content);
            link.download = "route.kmz";
            link.click();
        });
    };

    window.getRouteLink = function() {
        const waypoints = control.getWaypoints().filter(w => w.latLng);
        if (waypoints.length < 2) {
            alert("Please set at least 2 points (Start and End)!");
            return;
        }

        // Build Google Maps Directions URL
        const coords = waypoints.map(wp => wp.latLng.lat + ',' + wp.latLng.lng);
        const url = 'https://www.google.com/maps/dir/' + coords.join('/');

        // Copy to clipboard
        navigator.clipboard.writeText(url).then(function() {
            alert("Route link copied to clipboard!");
        }, function(err) {
            alert("Could not copy text: ", err);
        });

        // Also set to input if exists (for form submission)
        const routeLinkInput = document.getElementById('simpleText');
        if (routeLinkInput) {
            routeLinkInput.value = url;
        }
    };

    // Search functionality
    const searchInput = document.getElementById('searchBox');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = searchInput.value;
                if(!query) return;

                // Search with priority to Saudi Arabia
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&countrycodes=sa`)
                    .then(res => res.json())
                    .then(locations => {
                        if (locations.length) {
                            const loc = locations[0];
                            const latLng = [loc.lat, loc.lon];
                            map.setView(latLng, 15);

                            // Optional: Add a temporary marker or popup
                            L.popup()
                                .setLatLng(latLng)
                                .setContent(`<b>${loc.display_name}</b><br><button class="btn btn-xs btn-primary mt-2" onclick="addWaypointAt(${loc.lat}, ${loc.lon})">Add to Route</button>`)
                                .openOn(map);

                        } else {
                            // Fallback global search
                             fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
                                .then(res => res.json())
                                .then(globalLocs => {
                                    if(globalLocs.length) {
                                        const loc = globalLocs[0];
                                        const latLng = [loc.lat, loc.lon];
                                        map.setView(latLng, 15);
                                        L.popup()
                                            .setLatLng(latLng)
                                            .setContent(`<b>${loc.display_name}</b><br><button class="btn btn-xs btn-primary mt-2" onclick="addWaypointAt(${loc.lat}, ${loc.lon})">Add to Route</button>`)
                                            .openOn(map);
                                    } else {
                                        alert("Location not found.");
                                    }
                                });
                        }
                    });
            }
        });
    }

    // Helper to add waypoint from popup
    window.addWaypointAt = function(lat, lng) {
        const waypoints = control.getWaypoints().filter(w => w.latLng);
        waypoints.push({ latLng: L.latLng(lat, lng) });
        control.setWaypoints(waypoints);
        map.closePopup();
    };

    window.loadKMLFromUrl = function(url) {
        fetch(url)
            .then(res => res.text())
            .then(kmlText => {
                const parser = new DOMParser();
                const kmlDom = parser.parseFromString(kmlText, 'text/xml');
                const geoJson = toGeoJSON.kml(kmlDom);

                L.geoJSON(geoJson, {
                    style: { color: '#9b59b6', weight: 5, opacity: 0.7 },
                    onEachFeature: function (feature, layer) {
                        if (feature.properties.name) {
                            layer.bindPopup(feature.properties.name);
                        }
                    }
                }).addTo(map);
            })
            .catch(() => alert("Failed to load uploaded KML."));
    };
});

@if(session('uploaded_kml_url'))
    document.addEventListener('DOMContentLoaded', function() {
        if (window.loadKMLFromUrl) {
            window.loadKMLFromUrl(`{{ session('uploaded_kml_url') }}`);
        }
    });
@endif
</script>
@endpush
