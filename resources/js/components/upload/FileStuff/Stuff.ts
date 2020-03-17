class ChunkUpload {
    file: FileUpload
    ul: File
    start: number
    end: number
    constructor(file: FileUpload, start: number, end: number) {
        this.file = file
        this.ul = file;
        this.start = start;
        this.end = end;
    }
}

export class ulManager {
    private ulFaId: number
    private ulSize: number
    private ulIDToNode: Object
    private ulEventData: Object
    private isUploading: boolean
    private ulSetupQueue: boolean
    private ulStartingPhase: boolean
    private ulCompletingPhase: Object
    private ulOverStorageQuota: boolean
    private ulOverStorageQueue: string[]
    private ulBlockSize: number
    private ulBlockExtraSize: number
    private ulMaxFastTrackSize: number
    private ulMaxConcurrentSize: number
    private index: number
    private reader: FileReader
    private cache: Uint8Array[]
    file: FileUpload
    xhr: XMLHttpRequest
    constructor(file: FileUpload) {
        this.ulFaId = 0
        this.ulSize = 0
        this.ulIDToNode = Object.create(null)
        this.ulEventData = Object.create(null)
        this.isUploading = false
        this.ulSetupQueue = false
        this.ulStartingPhase = false
        this.ulCompletingPhase = Object.create(null)
        this.ulOverStorageQuota = false
        this.ulOverStorageQueue = []
        this.ulBlockSize = 131072
        this.ulBlockExtraSize = 1048576
        this.ulMaxFastTrackSize = 1048576 * 3
        this.ulMaxConcurrentSize = 1048576 * 10
        this.index = 0
        this.reader = new FileReader()
        this.cache = []
        this.file = file
        this.xhr = new XMLHttpRequest
    }

    ulpload() {
        if (this.file.size) {
            this.file.ul_offsets = []
            var self = this
            var i: number
            var pp = 0
            var p = 0
            var tasks = Object.create(null)
            var ulBlockExtraSize = this.ulBlockExtraSize;


            for (i = 1; i <= 8 && p < this.file.size - i * this.ulBlockSize; i++) {
                tasks[p] = new ChunkUpload(this.file, p, i * this.ulBlockSize)
                pp = p
                p += i * this.ulBlockSize
            }

            while (p < this.file.size) {
                tasks[p] = new ChunkUpload(this.file, p, ulBlockExtraSize);
                pp = p;
                p += ulBlockExtraSize;
            }

            if (this.file.size - pp > 0) {
                tasks[pp] = new ChunkUpload(this.file, pp, this.file.size - pp);
            }

            // if (d) ulmanager.logger.info('ulTasks', tasks);
            Object.keys(tasks).forEach(function (k) {
                self.file.ul_offsets!.push({
                    byteOffset: parseInt(k),
                    byteLength: tasks[k].end,
                });
                // ulQueue.pushFirst(tasks[k]);
            });
            this.startUpload()
            console.log("done uploading")
        }
    }

    startUpload() {
        console.log(this.index)


        this.reader.onloadend = (e) => {
            if (e.target!.readyState !== FileReader.DONE) {
                return
            }
            if (e.target!.result instanceof ArrayBuffer) {
                var data = new Uint8Array(e.target!.result)
                this.cache.push(data)
                this.startUpload()
            }
            else {
                console.log("Filereader cannot read as Uint8Array. Something went very wrong")
            }
        }

        if (this.file.ul_offsets!.length > this.index) {
            var blob = this.file.slice(this.file.ul_offsets![this.index].byteOffset, this.file.ul_offsets![this.index].byteOffset + this.file.ul_offsets![this.index].byteLength)
            this.index++
            console.log(this.index)
    
            this.reader.readAsArrayBuffer(blob)
        }else{
            this.index = 0
            this.actuallyUpload()
        }

        // this.reader.onloadend = (e) => {
        //     if (e.target!.readyState !== FileReader.DONE) {
        //         return
        //     }
        //     if (e.target!.result instanceof ArrayBuffer) {
        //         var data = new Uint8Array(e.target!.result)
        //         let xhr = new XMLHttpRequest;
        //         xhr.onloadend = (response) => {
        //             if (this.index < this.file.ul_offsets!.length) {
        //                 this.startUpload()
        //             }
        //             // File is done uploading
        //             else {
        //                 console.log("done")
        //             }
        //         }
        //         xhr.open("POST", "/test", true);
        //         xhr.send(data);
        //     }
        //     else{
        //         console.log("Filereader cannot read as Uint8Array. Something went very wrong")
        //     }
        // }
        // this.reader.readAsArrayBuffer(blob)
    }

    actuallyUpload() {
        this.xhr.onloadend = (response) => {
            if (this.index < this.cache.length) {
                this.index++
                // console.log(this.cache[this.index])
                this.actuallyUpload()   
            }
            else{
                console.log("done")
            }
        }
        this.xhr.open("POST", "/test", true)
        this.xhr.send(this.cache[this.index])
    }
}

interface FileUpload extends File {
    // Those offsets will be used when chunking the file up to smaller pieces
    ul_offsets?: { byteOffset: number, byteLength: number }[]
}