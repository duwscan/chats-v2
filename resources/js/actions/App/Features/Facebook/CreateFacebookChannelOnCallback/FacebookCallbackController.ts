import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Features\Facebook\CreateFacebookChannelOnCallback\FacebookCallbackController::__invoke
* @see app/Features/Facebook/CreateFacebookChannelOnCallback/FacebookCallbackController.php:22
* @route '/api/facebook/oauth/callback'
*/
const FacebookCallbackController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: FacebookCallbackController.url(options),
    method: 'get',
})

FacebookCallbackController.definition = {
    methods: ["get","head"],
    url: '/api/facebook/oauth/callback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Facebook\CreateFacebookChannelOnCallback\FacebookCallbackController::__invoke
* @see app/Features/Facebook/CreateFacebookChannelOnCallback/FacebookCallbackController.php:22
* @route '/api/facebook/oauth/callback'
*/
FacebookCallbackController.url = (options?: RouteQueryOptions) => {
    return FacebookCallbackController.definition.url + queryParams(options)
}

/**
* @see \App\Features\Facebook\CreateFacebookChannelOnCallback\FacebookCallbackController::__invoke
* @see app/Features/Facebook/CreateFacebookChannelOnCallback/FacebookCallbackController.php:22
* @route '/api/facebook/oauth/callback'
*/
FacebookCallbackController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: FacebookCallbackController.url(options),
    method: 'get',
})

/**
* @see \App\Features\Facebook\CreateFacebookChannelOnCallback\FacebookCallbackController::__invoke
* @see app/Features/Facebook/CreateFacebookChannelOnCallback/FacebookCallbackController.php:22
* @route '/api/facebook/oauth/callback'
*/
FacebookCallbackController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: FacebookCallbackController.url(options),
    method: 'head',
})

export default FacebookCallbackController