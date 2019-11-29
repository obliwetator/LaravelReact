import React from 'react'

  

function NotFound (error) {
    switch (error.error.response.status) {
        case 404:
            return(
                <div>The summoner you are looking for can't be found.</div>
            )
        // Unknown error
        default:
            return(
                <div>An error occured.</div>
            )
    }

}

export default NotFound