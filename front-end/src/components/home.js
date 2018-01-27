import React, { Component } from 'react';
import Paper from 'material-ui/Paper';
import '../index.css';
import {geolocated} from 'react-geolocated';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import Tablecomp from './traintable';
const style = {
  height: 500,
  width: 500,
  margin: 20,
  textAlign: 'center',
  display: 'inline-block',
};
class home extends Component {
	constructor(props){
		super(props);
		this.state = {
			type:'train',
			trainNo: '',
			onTrain:'',
			latitude:'',
			longitude:'',
			load:'',
			tab_load:''
		};
		this.handleChange = this.handleChange.bind(this);
		this.onTrain = this.onTrain.bind(this);
		this.notOnTrain = this.notOnTrain.bind(this);
		this.stopIt = this.stopIt.bind(this);
	}
	componentDidMount(){
		this.ws = new WebSocket('ws://127.0.0.1:9300');
	}
	handleChange = (e)=>{
		this.setState({train_no:e.target.value});
	}
	onTrain = ()=>{
		this.setState({onTrain:'yes'},()=>{
			this.ws.send(JSON.stringify(this.state));
			this.ws.onmessage = evt =>{
				console.log(evt.data);
			}
		});
	}
	notOnTrain = ()=>{
		this.setState({onTrain:'no'},()=>{
			this.ws.send(JSON.stringify(this.state));
			this.ws.onmessage = evt =>{
				this.setState({tab_load:evt.data});
				console.log(evt.data);
			}
		});

	}
	stopIt = ()=>{
		this.setState({type:'admin',load:'stop'},()=>{
			this.ws.send(JSON.stringify(this.state));
			this.ws.onmessage = evt =>{
				console.log(evt.data);
			}
		});
	}
	componentDidUpdate(previousProps, previousState){
		if(this.state.latitude !== this.props.coords.latitude && this.state.longitude !== this.props.coords.longitude) {
	     this.setState({latitude:this.props.coords.latitude,longitude:this.props.coords.longitude});
	   }

	}
	render() {
		return (
			<div className="hp">

				<Paper className="paper" style={style} zDepth={4}>
					<TextField floatingLabelText="Train number" ref="trainno" onChange={this.handleChange}/> <br/>
			        <RaisedButton label="On Train" onClick={this.onTrain} /><br/>
			        <RaisedButton label="Not on Train"  onClick={this.notOnTrain}/><br/>
			        <RaisedButton label="Stop the server"  onClick={this.stopIt}/>

			        {!this.props.isGeolocationAvailable
		      ? <div>Your browser does not support Geolocation</div>
		      : !this.props.isGeolocationEnabled
		        ? <div>Geolocation is not enabled</div>
		        : this.props.coords
		          ? <div></div>
		          : <div>Loading up the app, please wait&hellip; </div>
		      }
				</Paper>
				<Tablecomp data={this.state.tab_load}/>
      		</div>
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
})(home);