import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import MyIcon from './myicon';
import ProductBlockPreview from './ProductBlockPreview';
import './editor.scss';
import './style.scss';

const Edit = ({ attributes, setAttributes }) => {
  const { heading, productIds } = attributes;
  const [products, setProducts] = useState([]);

  useEffect(() => {
    apiFetch({ path: '/wc/v3/products?per_page=100' }).then(setProducts);
  }, []);

  const productOptions = products.map(product => ({
    label: product.name,
    value: product.id,
  }));

  return (
    <div {...useBlockProps()}>
      <InspectorControls>
        <PanelBody title={__('Block Settings', 'ac-plugin')}>
          <TextControl
            label={__('Heading', 'ac-plugin')}
            value={heading}
            onChange={value => setAttributes({ heading: value })}
          />
          <SelectControl
            multiple
            label={__('Select Products', 'ac-plugin')}
            value={productIds}
            options={productOptions}
            onChange={value => setAttributes({ productIds: value })}
          />
        </PanelBody>
      </InspectorControls>
      <ProductBlockPreview heading={heading} productIds={productIds} />
    </div>
  );
};

registerBlockType('ac-plugin/ac-product-block', {
  title: __('Product Block', 'ac-plugin'),
  icon: <MyIcon />,
  category: 'woocommerce',
  description: 'Create a grid of selected products with featured image, title and price.',
  attributes: {
    heading: {
      type: 'string',
      default: 'Featured Products',
    },
    productIds: {
      type: 'array',
      default: [],
    },
  },
  example: {},
  edit: Edit,
  save: () => null,
});