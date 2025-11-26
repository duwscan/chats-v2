import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Features\Line\UpdateLineChannel\UpdateLineChannelController::__invoke
* @see app/Features/Line/UpdateLineChannel/UpdateLineChannelController.php:22
* @route '/api/line/channel/{configId}'
*/
const UpdateLineChannelController = (args: { configId: string | number } | [configId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: UpdateLineChannelController.url(args, options),
    method: 'put',
})

UpdateLineChannelController.definition = {
    methods: ["put"],
    url: '/api/line/channel/{configId}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Line\UpdateLineChannel\UpdateLineChannelController::__invoke
* @see app/Features/Line/UpdateLineChannel/UpdateLineChannelController.php:22
* @route '/api/line/channel/{configId}'
*/
UpdateLineChannelController.url = (args: { configId: string | number } | [configId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { configId: args }
    }

    if (Array.isArray(args)) {
        args = {
            configId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        configId: args.configId,
    }

    return UpdateLineChannelController.definition.url
            .replace('{configId}', parsedArgs.configId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Line\UpdateLineChannel\UpdateLineChannelController::__invoke
* @see app/Features/Line/UpdateLineChannel/UpdateLineChannelController.php:22
* @route '/api/line/channel/{configId}'
*/
UpdateLineChannelController.put = (args: { configId: string | number } | [configId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: UpdateLineChannelController.url(args, options),
    method: 'put',
})

export default UpdateLineChannelController