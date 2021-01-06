import UpdateResource from './views/Edit'
import CreateResource from './views/Create'


Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'categories',
      path: '/categories',
      component: require('./components/Tool'),
    },
    {
        name: "category-detail",
        path: '/categories/:categoryId/view',
        component: require('./views/Detail'),
        props : true
    },
    {
        name: 'category-edit',
        path: '/categories/:categoryId/edit',
        component: UpdateResource,
        props: route => {
          return {
            resourceName: 'categories',
            resourceId: route.params.categoryId,
            viaResource: route.query.viaResource || '',
            viaResourceId: route.query.viaResourceId || '',
            viaRelationship: route.query.viaRelationship || '',
          }
        },
    },
    {
        name: 'category-create',
        path: '/categories/new',
        component: CreateResource,
        props: route => {
          return {
            resourceName: 'categories',
            viaResource: route.query.viaResource || '',
            viaResourceId: route.query.viaResourceId || '',
            viaRelationship: route.query.viaRelationship || '',
          }
        },
      },
  ])
})
