import React from "../../vendor/react/react.index";
import {render, unmountComponentAtNode} from "react-dom";
import {usePaginatedFetch} from "../hooks/useComments";
import {useEffect} from "react";
import {Icon} from "../components/Icon";
import {createRoot} from "react-dom/client";

function Comment() {
    const {items: comments, load, loading, count} = usePaginatedFetch('/api/comments')

    useEffect(() => {
        load()
    }, []);

    return (
        <div>
            {loading && 'Loading ...'}
            { JSON.stringify(comments)}
            <Title count={count}/>
            <button onClick={load}>Load the comments</button>
        </div>
    );
}

function Title({count}){
    return (
        <h3>
            <Icon icon={"comments"}/>
            {count} comment{count > 1 ? 's' : ''}
        </h3>
    )
}


class CommentsElements extends HTMLElement {
    connectedCallback() {
        const root = createRoot(this);
        root.render(<Comment/>);
    }

    disconnectdCallback(){
        unmountComponentAtNode(this)
    }

}

customElements.define('post-comments', CommentsElements);