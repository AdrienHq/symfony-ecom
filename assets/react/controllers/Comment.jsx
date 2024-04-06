import React from "../../vendor/react/react.index";
import {render, unmountComponentAtNode} from "react-dom";
import {usePaginatedFetch} from "../hooks/useComments";
import {useEffect} from "react";
import {Icon} from "../components/Icon";
import {createRoot} from "react-dom/client";

const dateFormat = {
    dateStyle: 'medium',
    timeStyle: 'short',
}

function Comments({recipe, user}) {
    const {items: comments, load, loading, count, hasMore} = usePaginatedFetch('/api/comments?recipe=' + recipe)

    useEffect(() => {
        load()
    }, []);

    return (
        <div>
            <Title count={count}/>
            {comments.map(c => <Comment key={c.id} comment={c}/>)}
            {hasMore &&
                <button disabled={loading} className="btn btn-primary" onClick={load}>
                    Load more comments
                </button>
            }
        </div>
    );
}

function Title({count}) {
    return (
        <h3 className="mt-5">
            <u>
                Your comment{count > 1 ? 's' : ''} & question{count > 1 ? 's' : ''} ({count})
            </u>
        </h3>
    )
}

const Comment = React.memo(({comment}) => {
    const date = new Date(comment.publishedAt)
    return (
        <div className="row mt-5 ">
            <div className="col-sm-9">
                <h3><strong>{comment.author.username}</strong></h3>
            </div>
            <div className="col-sm-9">
                <p>{comment.content}</p>
            </div>
            <div className="col-sm-9">
                <h4>Posted at : <strong>{date.toLocaleString(undefined, dateFormat)}</strong></h4>
            </div>
        </div>
    )

})


class CommentsElements extends HTMLElement {
    connectedCallback() {
        const recipe = parseInt(this.dataset.recipe, 10);
        const user = parseInt(this.dataset.user, 10);

        const root = createRoot(this);
        root.render(<Comments recipe={recipe} user={user} />);
    }

    disconnectedCallback() {
        unmountComponentAtNode(this)
    }

}

customElements.define('recipe-comments', CommentsElements);