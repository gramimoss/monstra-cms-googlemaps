<?php

    /**
     *	Maps plugin
     *
     *	@package Monstra
     *  @subpackage Plugins
     *	@author Graeme Moss / Gambi
     *	@copyright 2014 Graeme Moss / Gambi
     *	@version 1.0.0
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('GoogleMaps'),
                    __('Maps plugin for Monstra.'),  
                    '1.0.0',
                    'Gambi',                 
                    'http://www.gambi.co.za');
  


    // Add hook
	Action::add('theme_header', 'GoogleMaps::themesHeaders');

    // Add shortcode
    Shortcode::add('googlemap', 'GoogleMaps::_shortcode');


		/**
		 * Maps Shortcode
		 *
		 *  <code>
		 *      {googlemap latlng="-28.4792625,24.6727135" markers="Point1,-28.7197555,24.7763009"}
		 *
		 *      {googlemap height="480" latlng="-28.4792625,24.6727135" markers="point1,-28.7197555,24.7763009|point2,-28.7228392,24.7570326"}
		 *
		 *      {googlemap height="480" latlng="-28.4792625,24.6727135" polylines="-29.60465,30.33349/-29.61269,30.34017|-29.61269,30.34017/-29.60604,30.36988"}
		 *
		 *      {googlemap height="480" latlng="-28.4792625,24.6727135" markers="Backup Site,-29.60465,30.33349|main Site,-29.61269,30.34017" polylines="-29.60465,30.33349/-29.61269,30.34017|-29.61269,30.34017/-29.60604,30.36988"}
		 *  </code>
		 *
		 */
    class GoogleMaps {
        
		/**
         * Maps Headers
		 */
		public static function themesHeaders() {
			echo ('<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>');
		}
   

		/**
		 * Maps Shortcode
		 */
        public static function _shortcode($attributes) {
            // Extract
            extract($attributes);
            
            //UID
			$uid = Text::random(); 
		    
		    // markers
		    if (isset($markers)) {
		        $markers_string = '';            
		        $_markers = explode('|', $markers);
		        foreach($_markers as $d) {
		            $part = explode(',', $d);                
		            $markers_string .= '["'.$part[0].'", '.$part[1].', '.$part[2].'],';
		        }
		        
		        $markers = $markers_string;
		    } else {
		        $markers = '';
		    }
		    
		    // polylines
		    if (isset($polylines)) {
		        $polylines_string = '';            
		        $_polylines = explode('|', $polylines);
		        foreach($_polylines as $d) {
					$part = explode('/', $d);    
					$polylines_string .= '["'.$part[0].'", "'.$part[1].'"],';         
		        }
		        
		        $polylines = $polylines_string;
		    } else {
		        $polylines = '';
		    }

		    // Title
		    if (isset($latlng)) $latlng = $latlng; else $latlng = '-28.4792625,24.6727135';

		    // Zoom
		    if (isset($zoom)) $zoom = $zoom; else $zoom = 8;

		    // Height
		    if (isset($height)) $height = $height; else $height = 300;
		    
		    // Map
		    return ('<style> html, body, #map-canvas_'.$uid.' { margin: 0; padding: 0; height: 100%; } #map-canvas_'.$uid.' { width: 100%; height: '.$height.'px;	}</style>
							 
						<script type="text/javascript">
						var map'.$uid.';
						function initialize() {
						  var mapOptions = {
							zoom: '.$zoom.',
							center: new google.maps.LatLng('.$latlng.'),
							mapTypeId: google.maps.MapTypeId.TERRAIN
						  };
						  map'.$uid.' = new google.maps.Map(document.getElementById("map-canvas_'.$uid.'"),
							  mapOptions);
							  
						var datamakers = [
						  '.$markers.'         
						];
	
						var datapolylines = [
						  '.$polylines.'         
						];
	
						for (var i = 0; i < datamakers.length; i++) {
							var datamaker = datamakers[i];
							var myLatLng = new google.maps.LatLng(datamaker[1], datamaker[2]);
							var marker = new google.maps.Marker({
							position: myLatLng,
							map: map'.$uid.',
							title: datamaker[0],
							});
						}
						
						for (var i = 0; i < datapolylines.length; i++) {
							var datapolyline = datapolylines[i];
							pointa = datapolyline[0].split(",");
						    pointb = datapolyline[1].split(",");
						    console.log(pointa);
						    console.log(pointb);
							var datapolylineCoordinates = [
								new google.maps.LatLng(pointa[0],pointa[1]),
								new google.maps.LatLng(pointb[0],pointb[1])
								];
								console.log(datapolylineCoordinates);
							var polylinePath = new google.maps.Polyline({
							path: datapolylineCoordinates,
							geodesic: true,
							map: map'.$uid.',
							strokeColor: "#FF0000",
							strokeOpacity: 1.0,
							strokeWeight: 2
						    });	 
						}

						}

						google.maps.event.addDomListener(window, "load", initialize);

					</script>
		            <div id="map-canvas_'.$uid.'" class="" contenteditable="false"></div>');  
		    
		    
		    
		}

    }
