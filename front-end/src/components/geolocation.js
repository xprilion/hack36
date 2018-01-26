import React, { Component } from 'react';
import {geolocated} from 'react-geolocated';

class geolocation extends Component {
	render() {
		return (
			!this.props.isGeolocationAvailable
		      ? <div>Your browser does not support Geolocation</div>
		      : !this.props.isGeolocationEnabled
		        ? <div>Geolocation is not enabled</div>
		        : this.props.coords
		          ? <table>
		            <tbody>
		              <tr><td>latitude</td><td>{this.props.coords.latitude}</td></tr>
		              <tr><td>longitude</td><td>{this.props.coords.longitude}</td></tr>
		              <tr><td>altitude</td><td>{this.props.coords.altitude}</td></tr>
		              <tr><td>heading</td><td>{this.props.coords.heading}</td></tr>
		              <tr><td>speed</td><td>{this.props.coords.speed}</td></tr>
		            </tbody>
		          </table>
		          : <div>Loading up the app&hellip; </div>
		);
	}
}
export default geolocated({
  positionOptions: {
    enableHighAccuracy: false,
  },
  watchPosition: true,
  userDecisionTimeout: null,
  suppressLocationOnMount: false,
  geolocationProvider: navigator.geolocation
})(geolocation);