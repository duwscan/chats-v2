import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Features\Line\HandleLineWebhook\HandleLineWebhookController::__invoke
* @see app/Features/Line/HandleLineWebhook/HandleLineWebhookController.php:32
* @route '/api/webhook/line/{userWebsiteId}/{configId}'
*/
const HandleLineWebhookController = (args: { userWebsiteId: string | number, configId: string | number } | [userWebsiteId: string | number, configId: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: HandleLineWebhookController.url(args, options),
    method: 'post',
})

HandleLineWebhookController.definition = {
    methods: ["post"],
    url: '/api/webhook/line/{userWebsiteId}/{configId}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Line\HandleLineWebhook\HandleLineWebhookController::__invoke
* @see app/Features/Line/HandleLineWebhook/HandleLineWebhookController.php:32
* @route '/api/webhook/line/{userWebsiteId}/{configId}'
*/
HandleLineWebhookController.url = (args: { userWebsiteId: string | number, configId: string | number } | [userWebsiteId: string | number, configId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            userWebsiteId: args[0],
            configId: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        userWebsiteId: args.userWebsiteId,
        configId: args.configId,
    }

    return HandleLineWebhookController.definition.url
            .replace('{userWebsiteId}', parsedArgs.userWebsiteId.toString())
            .replace('{configId}', parsedArgs.configId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Line\HandleLineWebhook\HandleLineWebhookController::__invoke
* @see app/Features/Line/HandleLineWebhook/HandleLineWebhookController.php:32
* @route '/api/webhook/line/{userWebsiteId}/{configId}'
*/
HandleLineWebhookController.post = (args: { userWebsiteId: string | number, configId: string | number } | [userWebsiteId: string | number, configId: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: HandleLineWebhookController.url(args, options),
    method: 'post',
})

export default HandleLineWebhookController