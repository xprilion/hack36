import React, { Component } from 'react';
import Homepage from './components/home';
import Contribute from './components/contribute';
import Find from './components/find';
import AppBar from 'material-ui/AppBar';
import Drawer from 'material-ui/Drawer';
import MenuItem from 'material-ui/MenuItem';
import { Link } from 'react-router-dom';
import { Switch,Route } from 'react-router-dom';
class App extends Component {
	constructor(props){
		super(props);
		this.state={open:false};
		this.toggleBar = this.toggleBar.bind(this);
	}
	toggleBar=()=>{
		this.setState({open:!this.state.open});
	}
  render() {
    return (
      <div className="App">
      			<AppBar title="Track" onLeftIconButtonClick={this.toggleBar}/>
	      		<Drawer onRequestChange={(open)=>this.state.open} open={this.state.open} docked={false} >
	      			<MenuItem onClick={this.toggleBar}><Link className="linking" to="/contibute">Share your details</Link></MenuItem>
	      			<MenuItem onClick={this.toggleBar}><Link className="linking" to="/find">Find train location</Link></MenuItem>
	      		</Drawer>
        <Switch>
        	<Route exact path="/" component={Homepage}/>
        	<Route exact path="/contibute" component={Contribute}/>
        	<Route exact path="/find" component={Find}/>
        </Switch>
      </div>
    );
  }
}

export default App;
