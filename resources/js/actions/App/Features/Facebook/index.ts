import GenerateFacebookConnectUrl from './GenerateFacebookConnectUrl'
import CreateFacebookChannelOnCallback from './CreateFacebookChannelOnCallback'
import HandleWebhook from './HandleWebhook'
import VerifyFacebookWebhook from './VerifyFacebookWebhook'
import SendFacebookMessage from './SendFacebookMessage'

const Facebook = {
    GenerateFacebookConnectUrl: Object.assign(GenerateFacebookConnectUrl, GenerateFacebookConnectUrl),
    CreateFacebookChannelOnCallback: Object.assign(CreateFacebookChannelOnCallback, CreateFacebookChannelOnCallback),
    HandleWebhook: Object.assign(HandleWebhook, HandleWebhook),
    VerifyFacebookWebhook: Object.assign(VerifyFacebookWebhook, VerifyFacebookWebhook),
    SendFacebookMessage: Object.assign(SendFacebookMessage, SendFacebookMessage),
}

export default Facebook