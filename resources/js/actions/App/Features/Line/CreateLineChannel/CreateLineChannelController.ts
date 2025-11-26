import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Features\Line\CreateLineChannel\CreateLineChannelController::__invoke
* @see app/Features/Line/CreateLineChannel/CreateLineChannelController.php:22
* @route '/api/line/channel'
*/
const CreateLineChannelController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: CreateLineChannelController.url(options),
    method: 'post',
})

CreateLineChannelController.definition = {
    methods: ["post"],
    url: '/api/line/channel',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Line\CreateLineChannel\CreateLineChannelController::__invoke
* @see app/Features/Line/CreateLineChannel/CreateLineChannelController.php:22
* @route '/api/line/channel'
*/
CreateLineChannelController.url = (options?: RouteQueryOptions) => {
    return CreateLineChannelController.definition.url + queryParams(options)
}

/**
* @see \App\Features\Line\CreateLineChannel\CreateLineChannelController::__invoke
* @see app/Features/Line/CreateLineChannel/CreateLineChannelController.php:22
* @route '/api/line/channel'
*/
CreateLineChannelController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: CreateLineChannelController.url(options),
    method: 'post',
})

export default CreateLineChannelController