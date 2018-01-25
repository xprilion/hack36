import React, { Component } from 'react';
import Paper from 'material-ui/Paper';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
const style = {
  height: 500,
  width: 500,
  margin: 20,
  textAlign: 'center',
  display: 'inline-block',
};
class find extends Component {
	render() {
		return (
			<div className="hp">
			<Paper className="paper" style={style} zDepth={4}>
					Find the location of the train.
					<br/>
					<form onSubmit={this.handleSubmit}>
			          <TextField floatingLabelText="Train number" onChange={this.change} />      
			          <RaisedButton label="Submit" type="submit" />
			        </form>
				</Paper></div>
		);
	}
}
export default find;