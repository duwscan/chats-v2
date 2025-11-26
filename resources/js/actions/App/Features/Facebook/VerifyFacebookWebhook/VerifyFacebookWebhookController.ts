import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Features\Facebook\VerifyFacebookWebhook\VerifyFacebookWebhookController::__invoke
* @see app/Features/Facebook/VerifyFacebookWebhook/VerifyFacebookWebhookController.php:20
* @route '/api/webhook/facebook'
*/
const VerifyFacebookWebhookController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: VerifyFacebookWebhookController.url(options),
    method: 'get',
})

VerifyFacebookWebhookController.definition = {
    methods: ["get","head"],
    url: '/api/webhook/facebook',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Facebook\VerifyFacebookWebhook\VerifyFacebookWebhookController::__invoke
* @see app/Features/Facebook/VerifyFacebookWebhook/VerifyFacebookWebhookController.php:20
* @route '/api/webhook/facebook'
*/
VerifyFacebookWebhookController.url = (options?: RouteQueryOptions) => {
    return VerifyFacebookWebhookController.definition.url + queryParams(options)
}

/**
* @see \App\Features\Facebook\VerifyFacebookWebhook\VerifyFacebookWebhookController::__invoke
* @see app/Features/Facebook/VerifyFacebookWebhook/VerifyFacebookWebhookController.php:20
* @route '/api/webhook/facebook'
*/
VerifyFacebookWebhookController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: VerifyFacebookWebhookController.url(options),
    method: 'get',
})

/**
* @see \App\Features\Facebook\VerifyFacebookWebhook\VerifyFacebookWebhookController::__invoke
* @see app/Features/Facebook/VerifyFacebookWebhook/VerifyFacebookWebhookController.php:20
* @route '/api/webhook/facebook'
*/
VerifyFacebookWebhookController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: VerifyFacebookWebhookController.url(options),
    method: 'head',
})

export default VerifyFacebookWebhookController