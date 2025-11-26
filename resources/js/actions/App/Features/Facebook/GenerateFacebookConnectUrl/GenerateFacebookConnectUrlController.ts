import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Features\Facebook\GenerateFacebookConnectUrl\GenerateFacebookConnectUrlController::__invoke
* @see app/Features/Facebook/GenerateFacebookConnectUrl/GenerateFacebookConnectUrlController.php:21
* @route '/api/facebook/oauth/{userWebsiteId}/url'
*/
const GenerateFacebookConnectUrlController = (args: { userWebsiteId: string | number } | [userWebsiteId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: GenerateFacebookConnectUrlController.url(args, options),
    method: 'get',
})

GenerateFacebookConnectUrlController.definition = {
    methods: ["get","head"],
    url: '/api/facebook/oauth/{userWebsiteId}/url',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Facebook\GenerateFacebookConnectUrl\GenerateFacebookConnectUrlController::__invoke
* @see app/Features/Facebook/GenerateFacebookConnectUrl/GenerateFacebookConnectUrlController.php:21
* @route '/api/facebook/oauth/{userWebsiteId}/url'
*/
GenerateFacebookConnectUrlController.url = (args: { userWebsiteId: string | number } | [userWebsiteId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { userWebsiteId: args }
    }

    if (Array.isArray(args)) {
        args = {
            userWebsiteId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        userWebsiteId: args.userWebsiteId,
    }

    return GenerateFacebookConnectUrlController.definition.url
            .replace('{userWebsiteId}', parsedArgs.userWebsiteId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Facebook\GenerateFacebookConnectUrl\GenerateFacebookConnectUrlController::__invoke
* @see app/Features/Facebook/GenerateFacebookConnectUrl/GenerateFacebookConnectUrlController.php:21
* @route '/api/facebook/oauth/{userWebsiteId}/url'
*/
GenerateFacebookConnectUrlController.get = (args: { userWebsiteId: string | number } | [userWebsiteId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: GenerateFacebookConnectUrlController.url(args, options),
    method: 'get',
})

/**
* @see \App\Features\Facebook\GenerateFacebookConnectUrl\GenerateFacebookConnectUrlController::__invoke
* @see app/Features/Facebook/GenerateFacebookConnectUrl/GenerateFacebookConnectUrlController.php:21
* @route '/api/facebook/oauth/{userWebsiteId}/url'
*/
GenerateFacebookConnectUrlController.head = (args: { userWebsiteId: string | number } | [userWebsiteId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: GenerateFacebookConnectUrlController.url(args, options),
    method: 'head',
})

export default GenerateFacebookConnectUrlController