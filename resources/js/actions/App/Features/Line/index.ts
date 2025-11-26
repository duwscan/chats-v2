import CreateLineChannel from './CreateLineChannel'
import UpdateLineChannel from './UpdateLineChannel'
import HandleLineWebhook from './HandleLineWebhook'
import SendLineMessage from './SendLineMessage'

const Line = {
    CreateLineChannel: Object.assign(CreateLineChannel, CreateLineChannel),
    UpdateLineChannel: Object.assign(UpdateLineChannel, UpdateLineChannel),
    HandleLineWebhook: Object.assign(HandleLineWebhook, HandleLineWebhook),
    SendLineMessage: Object.assign(SendLineMessage, SendLineMessage),
}

export default Line