import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Features\Facebook\SendFacebookMessage\SendFacebookMessageController::__invoke
* @see app/Features/Facebook/SendFacebookMessage/SendFacebookMessageController.php:25
* @route '/api/facebook/message/send'
*/
const SendFacebookMessageController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: SendFacebookMessageController.url(options),
    method: 'post',
})

SendFacebookMessageController.definition = {
    methods: ["post"],
    url: '/api/facebook/message/send',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Facebook\SendFacebookMessage\SendFacebookMessageController::__invoke
* @see app/Features/Facebook/SendFacebookMessage/SendFacebookMessageController.php:25
* @route '/api/facebook/message/send'
*/
SendFacebookMessageController.url = (options?: RouteQueryOptions) => {
    return SendFacebookMessageController.definition.url + queryParams(options)
}

/**
* @see \App\Features\Facebook\SendFacebookMessage\SendFacebookMessageController::__invoke
* @see app/Features/Facebook/SendFacebookMessage/SendFacebookMessageController.php:25
* @route '/api/facebook/message/send'
*/
SendFacebookMessageController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: SendFacebookMessageController.url(options),
    method: 'post',
})

export default SendFacebookMessageController