import * as React from 'react'
import * as ReactDOM from 'react-dom'
import { BrowserRouter as Router } from "react-router-dom"
import Axios from 'axios'

import {ulManager} from './FileStuff/Stuff'

// import Counter from './Counter'
// import 'react-dropzone-uploader/dist/styles.css'
// import Dropzone from 'react-dropzone-uploader'

interface Props {

}

interface State {
	a?: string
}

class Index extends React.Component<Props, State>{
	constructor(props: Props) {
		super(props)
		this.state = {
		}
		// this.handleDragStart = this.handleDragStart.bind(this)
		// this.handleDrop = this.handleDrop.bind(this)


	}
	componentDidMount() {
		document.title = 'Welcome'
	}

	handleDragEnter = (e: React.DragEvent<HTMLDivElement>) => {
		e.preventDefault()
		e.stopPropagation()
		e.persist()
		console.log("handleDragEnter")
	}

	handleDragLeave = (e: React.DragEvent<HTMLDivElement>) => {
		e.preventDefault()
		e.stopPropagation()
		e.persist()
		console.log("DragLeave")
	}
	handleDragOver = (e: React.DragEvent<HTMLDivElement>) => {
		e.preventDefault()
		e.stopPropagation()
		e.persist()
		console.log("DragOver")
	}

	handleDrop = (e: React.DragEvent<HTMLDivElement>) => {
		e.preventDefault()
		e.stopPropagation()
		e.persist()
		let dt = e.dataTransfer
		let files = dt.files
		let url = dt.getData('text/uri-list')
		// If the dragged element is an url
		if (url) {
			this.handleUrl(url)
		}
		else {
			([...files]).forEach(file => {
				var FileManager = new ulManager(file)
				FileManager.ulpload()
			})
		}

	}

	handleUrl = (url: string) => {
		if (url.substr(0, 5) == 'data:') {
			// handle base64 URIs
			return
		}
		// Regex pattern that matches common image 
		var regex = /(?:([^:\/?#]+):)?(?:\/\/([^\/?#]*))?([^?#]*\.?(jpe?g|gif|a?png|tiff?|bmp|xcf|webp))?(?:\?([^#]*))?(?:#(.*))?/
		let config = {
			onUploadProgress: (progressEvent: ProgressEvent) => {
				let percentCompleted = Math.floor((progressEvent.loaded * 100) / progressEvent.total)
				console.log(percentCompleted)
			}
		}
		Axios.post('/test', { url: url }, config)
			.then((request) => {
				console.log(request)
			})
			.catch((error) => {
				console.log(error)
			})
	}

	handleChange = (e: React.DragEvent<HTMLDivElement>) => {
		e.preventDefault()
		e.stopPropagation()
	}
	render() {
		return (
			<Router>
				<div id={'drop-area'} className={'a'}
					onDrop={this.handleDrop}
					onDragEnter={this.handleDragEnter}
					onDragLeave={this.handleDragLeave}
					onDragOver={this.handleDragOver}				>
				</div>
			</Router>
		)
	}
}

if (document.getElementById('index')) {
	ReactDOM.render(<Index />, document.getElementById('index'))
}