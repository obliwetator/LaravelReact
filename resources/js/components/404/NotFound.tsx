import * as React from 'react'



function NotFound(error: any) {
    console.log(error)
    if (error.error.response) {
        
        switch (error.error.response.status) {
            case 404:
                return (
                    <div>The summoner you are looking for can't be found.</div>
                )
            // Unknown error
            default:
                return (
                    <div>An error occured.</div>
                )
        }
    }
    else {
        console.log(error.error)
        return (
            <>
                Unkown error occured
            </>
        )

    }
}

export default NotFound