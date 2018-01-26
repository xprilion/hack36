import React, { Component } from 'react';
import Paper from 'material-ui/Paper';
import '../index.css';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
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
			train_no: '',
			onTrain:''
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
		this.setState({onTrain:'yes'});
	}
	notOnTrain = ()=>{
		this.setState({onTrain:'no'});
	}
	stopIt = ()=>{
		this.setState({type:'admin'});
	}
	render() {
		return (
			<div className="hp">
				
				<Paper className="paper" style={style} zDepth={4}>
					<TextField floatingLabelText="Train number" ref="trainno" onChange={this.handleChange}/> <br/>     
			        <RaisedButton label="On Train" onClick={this.onTrain} /><br/>
			        <RaisedButton label="Not on Train"  onClick={this.notOnTrain}/><br/>
			        <RaisedButton label="Stop the server"  onClick={this.stopIt}/>
				</Paper>
      		</div>
		);
	}
}
export default home;