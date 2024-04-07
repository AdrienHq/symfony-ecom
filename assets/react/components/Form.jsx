import React from "react";

const className = (...arr) => arr.filter(Boolean).join(' ')

export const Field = React.forwardRef(({help, name, children, error, onChange, required, minLength}, ref) => {
    if (error) {
        help = error
    }

    return (
        <div className={className('form-group', error && 'has-error')}>
            <label htmlFor={name}>{children}</label>
            <textarea ref={ref} rows="5" className="form-control" name={name} id={name} onChange={onChange}
                      required={required} minLength={minLength}/>
            {help && <div className="help-block">{help}</div>}
        </div>
    )
})