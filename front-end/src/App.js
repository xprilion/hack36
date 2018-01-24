import React, { Component } from 'react';
import Homepage from './components/home';
import Contribute from './components/contribute';
import Find from './components/find';
import { Switch,Route,Link } from 'react-router-dom';
class App extends Component {
  render() {
    return (
      <div className="App">
      		<Homepage/>
        	<button><Link className="linking" to="/contibute">Share your details</Link></button>
        	<button><Link className="linking" to="/find">Find train location</Link></button>
        <Switch>
        	<Route exact path="/contibute" component={Contribute}/>
        	<Route exact path="/find" component={Find}/>
        </Switch>
      </div>
    );
  }
}

export default App;
