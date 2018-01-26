import React, { Component } from 'react';
import Location from './geolocation';
import Paper from 'material-ui/Paper';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import '../index.css';
const style = {
  height: 500,
  width: 500,
  margin: 20,
  textAlign: 'center',
  display: 'inline-block',
};

class contribute extends Component {
	render() {
		return (
			<div className="hp">
			<Paper className="paper" style={style} zDepth={4}>
				Contribute your location and details
				<form onSubmit={this.handleSubmit}>
			        <TextField floatingLabelText="Train number" onChange={this.change} />      
			        <RaisedButton label="Submit" type="submit" />
			    </form>
				<Location/>
			</Paper>
			</div>
		);
	}
}
export default contribute;