import * as React from 'react'



const NotFound = (error: any) => {
    if (error.response) { 
        switch (error.response.status) {
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
        return (
            <>
                Unkown error occured
            </>
        )

    }
}

export default NotFound