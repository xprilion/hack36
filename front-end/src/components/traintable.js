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
						{this.props.data.route.map(res=>{
							return(
								<TableRow>
									<TableRowColumn>{res.station.name}</TableRowColumn>
									<TableRowColumn>{res.day}</TableRowColumn>
									<TableRowColumn>{res.schdep}</TableRowColumn>
									<TableRowColumn>{res.actarr}</TableRowColumn>
									<TableRowColumn>{res.actdep}</TableRowColumn>
									<TableRowColumn>{res.status}</TableRowColumn>
								</TableRow>
								)
						})}
						<TableRow>
							<TableRowColumn>1</TableRowColumn>
							<TableRowColumn>2</TableRowColumn>
							<TableRowColumn>3</TableRowColumn>
							<TableRowColumn>4</TableRowColumn>
							<TableRowColumn>5</TableRowColumn>
							<TableRowColumn>6</TableRowColumn>
							<TableRowColumn>7</TableRowColumn>
						</TableRow>
					</TableBody>
				</Table>
			</div>
		);
	}
}
export default traintable;