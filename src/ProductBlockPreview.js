function ProductBlockPreview() {
  return (
    <div className="product-block">
      <h2 className="product-block__title">Featured Products</h2>
      <div className="product-block__grid">
        {[...Array(6)].map((_, index) => (
          // eslint-disable-next-line react/no-array-index-key
          <div key={index} className="product-block__item">
            <p>
              Product
              {" "}
              {index + 1}
            </p>
          </div>
        ))}
      </div>
    </div>
  );
}

export default ProductBlockPreview;
