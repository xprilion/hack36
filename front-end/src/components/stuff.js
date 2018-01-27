import React, { Component } from 'react';
import '../index.css';
class stuff extends Component {
	render() {
		return (
			<div className="stats">
			<div>distance : {this.props.data.answer && this.props.data.answer.dist}</div>
			<div>angleCurLast : {this.props.data.answer && this.props.data.answer.angleCurLast}</div>
			<div>anglePrevCur : {this.props.data.answer && this.props.data.answer.anglePrevCur}</div>
			<div>anglePrevNext : {this.props.data.answer && this.props.data.answer.anglePrevNext}</div>
			<div>score : {this.props.data.answer && this.props.data.answer.score}</div>
			</div>
		);
	}
}
export default stuff;