import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Features\Facebook\HandleWebhook\HandleFacebookWebhookController::__invoke
* @see app/Features/Facebook/HandleWebhook/HandleFacebookWebhookController.php:32
* @route '/api/webhook/facebook'
*/
const HandleFacebookWebhookController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: HandleFacebookWebhookController.url(options),
    method: 'post',
})

HandleFacebookWebhookController.definition = {
    methods: ["post"],
    url: '/api/webhook/facebook',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Facebook\HandleWebhook\HandleFacebookWebhookController::__invoke
* @see app/Features/Facebook/HandleWebhook/HandleFacebookWebhookController.php:32
* @route '/api/webhook/facebook'
*/
HandleFacebookWebhookController.url = (options?: RouteQueryOptions) => {
    return HandleFacebookWebhookController.definition.url + queryParams(options)
}

/**
* @see \App\Features\Facebook\HandleWebhook\HandleFacebookWebhookController::__invoke
* @see app/Features/Facebook/HandleWebhook/HandleFacebookWebhookController.php:32
* @route '/api/webhook/facebook'
*/
HandleFacebookWebhookController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: HandleFacebookWebhookController.url(options),
    method: 'post',
})

export default HandleFacebookWebhookController