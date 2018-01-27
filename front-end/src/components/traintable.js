import React, { Component } from 'react';
import { Table,TableBody,TableHeader,TableHeaderColumn,TableRow,TableRowColumn } from 'material-ui/Table';
 
class traintable extends Component {
	render() {
		return (
			<div>
				<Table>
					<TableHeader>
						<TableRow>
							<TableHeaderColumn>Station</TableHeaderColumn>
							<TableHeaderColumn>Day</TableHeaderColumn>
							<TableHeaderColumn>Sch Dep</TableHeaderColumn>
							<TableHeaderColumn>ETA/ATA</TableHeaderColumn>
							<TableHeaderColumn>ETD/ATD</TableHeaderColumn>
							<TableHeaderColumn>Delay</TableHeaderColumn>
						</TableRow>
					</TableHeader>
					<TableBody>
						{this.props.data.answer && this.props.data.answer.alldata.route.map(route=>{
							return(
								<TableRow key={route.station.lat}>
									<TableRowColumn>{route.station.name}</TableRowColumn>
									<TableRowColumn>{route.day}</TableRowColumn>
									<TableRowColumn>{route.schdep}</TableRowColumn>
									<TableRowColumn>{route.actarr}</TableRowColumn>
									<TableRowColumn>{route.actdep}</TableRowColumn>
									<TableRowColumn>{route.status}</TableRowColumn>
								</TableRow>
								)
						})}
					</TableBody>
				</Table>
			</div>
		);
	}
}
export default traintable;