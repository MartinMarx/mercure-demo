function appendMessageToDom(data) {
    let message = document.createElement("div")
    message.classList.add('item')
    if (data.sender === "{{ username }}") {
        message.classList.add('out')
    } else {
        message.classList.add('in')
    }
    let body = document.createElement('div')
    body.classList.add('body')
    let content = document.createElement("p")
    content.classList.add('content')
    content.innerText = data.text
    let datetime = document.createElement("p")
    datetime.classList.add('datetime')
    datetime.innerText = new Intl.DateTimeFormat('fr-FR', {
        dateStyle: 'short',
        timeStyle: 'short'
    }).format(new Date(data.dateSent))

    body.append(content)
    body.append(datetime)
    message.append(body)

    document.getElementById('messages').append(message)
}
