import React, { Component } from 'react';
import Paper from 'material-ui/Paper';
import '../index.css';
const style = {
  height: 500,
  width: 500,
  margin: 20,
  textAlign: 'center',
  display: 'inline-block',
};

class home extends Component {
	
	render() {
		return (
			<div className="hp">
				
				<Paper className="paper" style={style} zDepth={4}>
					Welcome to tracIT! we track your trains for you!
					<br/>
					Feel free to share your location to earn points as well.
				</Paper>
      		</div>
		);
	}
}
export default home;