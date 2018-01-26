import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import { BrowserRouter } from 'react-router-dom';
import App from './App';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
ReactDOM.render(<MuiThemeProvider><BrowserRouter><App /></BrowserRouter></MuiThemeProvider>, document.getElementById('root'));
