import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Features\Line\SendLineMessage\SendLineMessageController::__invoke
* @see app/Features/Line/SendLineMessage/SendLineMessageController.php:25
* @route '/api/line/message/send'
*/
const SendLineMessageController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: SendLineMessageController.url(options),
    method: 'post',
})

SendLineMessageController.definition = {
    methods: ["post"],
    url: '/api/line/message/send',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Line\SendLineMessage\SendLineMessageController::__invoke
* @see app/Features/Line/SendLineMessage/SendLineMessageController.php:25
* @route '/api/line/message/send'
*/
SendLineMessageController.url = (options?: RouteQueryOptions) => {
    return SendLineMessageController.definition.url + queryParams(options)
}

/**
* @see \App\Features\Line\SendLineMessage\SendLineMessageController::__invoke
* @see app/Features/Line/SendLineMessage/SendLineMessageController.php:25
* @route '/api/line/message/send'
*/
SendLineMessageController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: SendLineMessageController.url(options),
    method: 'post',
})

export default SendLineMessageController