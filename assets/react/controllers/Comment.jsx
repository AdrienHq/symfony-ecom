import React from "../../vendor/react/react.index";
import {unmountComponentAtNode} from "react-dom";
import {useFetch, usePaginatedFetch} from "../hooks/useComments";
import {useCallback, useEffect, useRef} from "react";
import {createRoot} from "react-dom/client";
import {Field} from "../components/Form";

const dateFormat = {
    dateStyle: 'medium',
    timeStyle: 'short',
}

function Comments({recipe, user}) {
    const {
        items: comments,
        setItems: setComments,
        load,
        loading,
        count,
        hasMore
    } = usePaginatedFetch('/api/comments?recipe=' + recipe)

    const addComment = useCallback(comments => {
        setComments(comments => [comment, ...comments])

    }, [])

    useEffect(() => {
        load()
    }, []);

    return (
        <div>
            <Title count={count}/>
            {user && <CommentForm recipe={recipe} onComment={addComment}/>}
            {comments.map(c => <Comment key={c.id} comment={c}/>)}
            {hasMore &&
                <button disabled={loading} className="btn btn-primary" onClick={load}>
                    Load more comments
                </button>
            }
        </div>
    );
}

const CommentForm = React.memo(({recipe, onComment}) => {
    const ref = useRef(null)
    const onSuccess = useCallback(comment => {
        onComment(comment)
        ref.current.value = ''
    }, [ref, onComment])

    const {load, loading, errors, clearError} = useFetch('/api/comments/'+recipe, 'POST', onSuccess)
    const onSubmit = useCallback(e => {
        e.preventDefault()
        load({
            content: ref.current.value,
            recipe: "/api/recipes/" + recipe
        })
    }, [load, ref, recipe])

    return (
        <div className="p-3 mt-4 mb-2 bg-secondary text-white border-4">
            <form className="m-2" onSubmit={onSubmit}>
                <fieldset>
                    <legend>
                        Leave a comment
                    </legend>
                </fieldset>
                <Field
                    className="m-2"
                    name="content"
                    help="Please be respectful in the comments section ! <3"
                    ref={ref} error="Your comment is too short"
                    required
                    minLength={5}
                    onChange={clearError.bind(this, 'content')}
                    error={errors['content']}
                >
                    Your comment
                </Field>
                <div className="form-group">
                    <button className="btn btn-primary disabled={loading}">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    )
})

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
        const user = parseInt(this.dataset.user, 10) || null;

        const root = createRoot(this);
        root.render(<Comments recipe={recipe} user={user}/>);
    }

    disconnectedCallback() {
        unmountComponentAtNode(this)
    }

}

customElements.define('recipe-comments', CommentsElements);