( function( blocks, element, data, blockEditor ) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        withSelect = data.withSelect,
        useBlockProps = blockEditor.useBlockProps;

    registerBlockType( 'gutenberg-examples/example-dynamic', {
        apiVersion: 2,
        title: 'Example: last post',
        icon: 'megaphone',
        category: 'widgets',
        edit: withSelect( function( select ) {
            return {
                posts: select( 'core' ).getEntityRecords( 'postType', 'post' ),
            };
        } )( function( props ) {
            var blockProps = useBlockProps();
            var content;
            if ( ! props.posts ) {
                content = 'Loading...';
            } else if ( props.posts.length === 0 ) {
                content = 'No posts';
            } else {
                var post = props.posts[ 0 ];
                content = el(
                    'a',
                    { href: post.link },
                    post.title.rendered
                );
            }

            return el(
                'div',
                blockProps,
                content
            );
        } ),
    } );
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.data,
    window.wp.blockEditor,
) );