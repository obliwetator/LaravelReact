import React, {Component, useCallback} from 'react'
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from "react-router-dom";
import Axios from 'axios';
// import 'react-dropzone-uploader/dist/styles.css'
// import Dropzone from 'react-dropzone-uploader'

export default class Index extends Component {

	constructor(props) {
		super(props);
		this.state = {};

		//this.handleDragStart = this.handleDragStart.bind(this)
		this.handleDrop = this.handleDrop.bind(this)
	}
	componentDidMount() {
		document.title = 'Welcome';
	}

	handleDragEnter = (e) => {
		e.preventDefault();
		e.stopPropagation();
		e.persist()
		console.log("DragEnter")
	}

	handleDragLeave = (e) => {
		e.preventDefault();
		e.stopPropagation();
		e.persist()
		console.log("DragLeave")
	}
	handleDragOver = (e) => {
		e.preventDefault();
		e.stopPropagation();
		console.log("DragOver")
		e.persist()
	}

	handleDrop = (e) => {
		e.preventDefault();
		e.stopPropagation();
		e.persist()
		let dt = e.dataTransfer
		let files = dt.files
		let url = dt.getData('text/uri-list');
		// If the dragged element is an url
		if (url) {
			this.handleUrl(url)
		}
		else {
			([...files]).forEach(file => {
				let url = '/test'
				let formData = new FormData()

				formData.append('file', file)

				let config = {
					onUploadProgress: progressEvent => {
					  let percentCompleted = Math.floor((progressEvent.loaded * 100) / progressEvent.total)
					  console.log(percentCompleted)
					}
				  }
				Axios.post(url, formData, config)
				.then((request) => {
					console.log(request)
				})
				.catch((error) => {
					console.log(error)
				})

			});
		}

	}

	handleUrl = (url) => {
		if (url.substr(0, 5) == 'data:') {
            // handle base64 URIs
            return;
		}
		// Regex pattern that matches common image 
		var regex = /(?:([^:\/?#]+):)?(?:\/\/([^\/?#]*))?([^?#]*\.?(jpe?g|gif|a?png|tiff?|bmp|xcf|webp))?(?:\?([^#]*))?(?:#(.*))?/;
		let config = {
			onUploadProgress: progressEvent => {
			  let percentCompleted = Math.floor((progressEvent.loaded * 100) / progressEvent.total)
			  console.log(percentCompleted)
			}
		  }
		Axios.post('/test', {url: url}, config)
		.then((request) => {
			console.log(request)
		})
		.catch((error) => {
			console.log(error)
		})
	}

	handleChange = (e) => {
		e.preventDefault();
		e.stopPropagation();
	}
	render() {
		return (
			<Router>
				<div id={'drop-area'} className={'a'}
					onDrop={this.handleDrop}
					onDragEnter={this.handleDragEnter}
					onDragLeave={this.handleDragLeave}
					onDragOver={this.handleDragOver}
				>
				</div>
			</Router>
		);
	}
}

// const Standard = () => {
//   	// specify upload params and url for your files
// 	  const getUploadParams = ({ file, meta}) => {
// 		const body = new FormData()
// 		body.append('file', file)
// 		return { url: '/test', body,
// 		 		headers: {
// 					 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content}
// 				}
// 	  }
//   	// called every time a file's `status` changes
//   	const handleChangeStatus = ({ meta, file }, status) => { console.log(status, meta, file) }
  
// 	const handleSubmit = (files, allFiles) => {
// 	  console.log(files.map(f => f.meta))
// 	  allFiles.forEach(f => f.remove())
// 	}
  
// 	return (
// 	  <Dropzone
// 		getUploadParams={getUploadParams}
// 		onChangeStatus={handleChangeStatus}
// 		onSubmit={handleSubmit}
// 		styles={{ dropzone: { minHeight: 200, maxHeight: 250 } }}
// 	  />
// 	)
//   }

if (document.getElementById('index')) {
	ReactDOM.render(<Index />, document.getElementById('index'));
}