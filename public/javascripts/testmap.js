// JavaScript Document

var MERCATOR_RANGE = 256;
 
function bound(value, opt_min, opt_max) {
  if (opt_min != null) value = Math.max(value, opt_min);
  if (opt_max != null) value = Math.min(value, opt_max);
  return value;
}
 
function degreesToRadians(deg) {
  return deg * (Math.PI / 180);
}
 
function radiansToDegrees(rad) {
  return rad / (Math.PI / 180);
}
 
function MercatorProjection() {
  this.pixelOrigin_ = new google.maps.Point(
      MERCATOR_RANGE / 2, MERCATOR_RANGE / 2);
  this.pixelsPerLonDegree_ = MERCATOR_RANGE / 360;
  this.pixelsPerLonRadian_ = MERCATOR_RANGE / (2 * Math.PI);
};
 
MercatorProjection.prototype.fromLatLngToPoint = function(latLng, opt_point) {
  var me = this;

  var point = opt_point || new google.maps.Point(0, 0);

  var origin = me.pixelOrigin_;
  point.x = origin.x + latLng.lng() * me.pixelsPerLonDegree_;
  // NOTE(appleton): Truncating to 0.9999 effectively limits latitude to
  // 89.189.  This is about a third of a tile past the edge of the world tile.
  var siny = bound(Math.sin(degreesToRadians(latLng.lat())), -0.9999, 0.9999);
  point.y = origin.y + 0.5 * Math.log((1 + siny) / (1 - siny)) * -me.pixelsPerLonRadian_;
  return point;
};
 
MercatorProjection.prototype.fromPointToLatLng = function(point) {
  var me = this;
  
  var origin = me.pixelOrigin_;
  var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
  var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
  var lat = radiansToDegrees(2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2);
  return new google.maps.LatLng(lat, lng);
};

  function initializeMap() {
    var map;
    var coordInfoWindow;
    var loscharros = new google.maps.LatLng(37.381209,-122.075575);
        var mapOptions = {
      zoom: 10,
      center: loscharros,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions); 
    
    var latlngStr = "Los Charros Cantina" +"<br />" + "LatLng: " + loscharros.lat() + " , " + loscharros.lng() + "<br />";
    var projection = new MercatorProjection();
    var worldCoordinate = projection.fromLatLngToPoint(loscharros);
    var worldCoordStr = "World Coordinate: " + worldCoordinate.x + " , " + worldCoordinate.y;
    var pixelCoordinate = new google.maps.Point(worldCoordinate.x * Math.pow(2, map.getZoom()), worldCoordinate.y * Math.pow(2, map.getZoom()));
    var pixelCoordStr = "<br />Pixel Coordinate: " + Math.floor(pixelCoordinate.x) + " , " + Math.floor(pixelCoordinate.y);
    var tileCoordinate = new google.maps.Point(Math.floor(pixelCoordinate.x / MERCATOR_RANGE), Math.floor(pixelCoordinate.y / MERCATOR_RANGE));
    var tileCoordStr = "<br />Tile Coordinate: " + tileCoordinate.x + " , " + tileCoordinate.y + " at Zoom Level: " + map.getZoom();
    
    coordInfoWindow = new google.maps.InfoWindow({content: "Los Charros Cantina<br />Address in Mountain View"});
    // coordInfoWindow.setContent(latlngStr + worldCoordStr + pixelCoordStr + tileCoordStr);
    coordInfoWindow.setPosition(loscharros);
    coordInfoWindow.open(map);

    google.maps.event.addListener(map, 'zoom_changed', function() {
      pixelCoordinate = new google.maps.Point(worldCoordinate.x * Math.pow(2, map.getZoom()), worldCoordinate.y * Math.pow(2, map.getZoom()));
      pixelCoordStr = "<br />Pixel Coordinate: " + Math.floor(pixelCoordinate.x) + " , " + Math.floor(pixelCoordinate.y);
      tileCoordinate = new google.maps.Point(Math.floor(pixelCoordinate.x / MERCATOR_RANGE), Math.floor(pixelCoordinate.y / MERCATOR_RANGE));
      tileCoordStr = "<br />Tile Coordinate: " + tileCoordinate.x + " , " + tileCoordinate.y  + " at Zoom Level: " + map.getZoom();
    
      // coordInfoWindow.setContent(latlngStr + worldCoordStr + pixelCoordStr +tileCoordStr);
      coordInfoWindow.open(map);
    });
  }