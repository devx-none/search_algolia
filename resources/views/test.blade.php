
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" xmlns:og="http://ogp.me/ns#">
    <head>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/reset-min.css" integrity="sha256-t2ATOGCtAIZNnzER679jwcFcKYfLlw01gli6F6oszk8=" crossorigin="anonymous">

    </head>

 

    <body>
 
 
 
        <div class="container-fluid top-line">
 
        
            <div style=" " class="mobile-parent-menu2 col-lg-6 col-md-6 col-sm-6 col-xs-10">


                  <!-- <form action="/search" method="GET" class="search_div">
                        @csrf
                        <div class="input-outer" style="background: #f6f6f6;margin: 5px 0px; border-radius: 40px;     width: 100%;  display: inline-block;">
                            <div  class="col-lg-11 col-md-10 col-sm-9 col-xs-9" style="padding: 0px;">
                                <input type="search" name="search" class="search_text form-control" value="" maxlength="128"  minlength="3"  required="required" style=" display: inline-block; padding: 0px 20px; margin: 0px; border-radius: 40px; border: 0px; height: 32px;">
                                <input type="hidden" name="page" value="1">

                            </div>
                            <div  class="col-lg-1 col-md-2 col-sm-3 col-xs-3" style="padding: 0px;">
                                <button type="submit" class="btn btn-border color-default" style="border: 0px; background-color:#bbe8b0; color: #165929; display: block;height: 32px;padding: 0px 6px;   width: auto;    border-radius: 40px;  " ><span>{{ __('messages.search') }}</span></button>
                            </div>

                            
                        </div> 
                    </form> -->

                    <form autocomplete="off" action="/search" method="GET">
                        @csrf
                        <div class="input-outer "   style="  margin: 15px 0px;  background: #f6f6f6; border-radius: 40px;  border: 1px solid #eee;  width: 100%;  display: inline-block;">
                            <div  class="col-lg-10 col-md-10 col-sm-10 col-xs-10" style="padding: 0px;">
                                <input type="search" name="search" id="search_input" class="search_text form-control" value="" maxlength="128" minlength="3"  placeholder="{{ __('messages.keyword') }}"   required="required" style=" display: inline-block; padding: 0px 20px; margin: 0px; border-radius: 40px; border: 0px; height: 50px;">
                                <input type="hidden" name="page" value="1">

                            </div>
                            <div  class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding: 0px;">
                                <button type="submit" class="btn btn-border color-default" style="  background-color: #165929; color: #fff; display: block;height: 50px;padding: 0px 10px;  width: 100%;    padding: 10px ;  border-radius: 40px;    " ><span>{{ __('messages.search') }}</span></button>
                            </div>


                            <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0px;top: 80px; display: block; position: absolute; background: #f6f6f8;    width: auto;    z-index: 999;" id="search-result">


                            </div>

                            
                        </div> 
                    </form>
        
            </div>
        </div>
   
 
 

 @yield('main_content')


@section('main_content') 
 

<hr>


    <script>

    

  function toggleElements({ closedElement, openedElement }) {
    closedElement.style.display = 'none'
    openedElement.style.display = 'block'
  }

  const searchClient = algoliasearch('KV84C6LQ6L', '1d2941d117d281701fdd3eebd06f8f78');
  const search = instantsearch({
    indexName: 'dev_ev',
    searchClient,
    searchFunction(helper) {
      if (helper.state.query) {
        document.getElementById('see-all-products-link').href = `/search?query=${encodeURI(helper.state.query)}`
        helper.search();
      }
    },
  });

  document.addEventListener("DOMContentLoaded", () => {

    const focusOverlay = document.getElementById('instant-search-focus')
    const hitsOverlay = document.getElementById('algolia-search-hits')
    const searchWithResult = document.getElementById('search-with-result')
    const searchWithoutResult = document.getElementById('search-without-result')
    const seeAllProductsButton = document.getElementById('see-all-products-button')

    hitsOverlay.addEventListener('click', function (e) {
      const el = e.target;
      const elHasAttribute = el.hasAttribute('data-attribute-index');

      if (elHasAttribute || el.parentNode.hasAttribute('data-attribute-index')) {
        const index = elHasAttribute
          ? el.getAttribute('data-attribute-index')
          : el.parentNode.getAttribute('data-attribute-index');

        const dataLayerObj = {
          algoliaUserToken: localStorage.getItem('algolia_user_token'),
          algoliaIndexName: index === 'brand'
            ? 'greenweez_france_prod_brands_fr_FR'
            : index === 'category'
              ? 'greenweez_france_prod_categories_fr_FR'
              : index === 'offer'
                ? 'dev_ev'
                : ''
        };

        dataLayer.push(dataLayerObj);
      }
    });

    const totalResults = {
      products: 0,
      brands: 0,
      categories: 0,
      sum() { return (this.products + this.brands + this.categories) > 0 },
      toggleSearchDivs() {
        if (this.sum() > 0) toggleElements({ closedElement: searchWithoutResult, openedElement: searchWithResult })
        if (this.sum() <= 0) toggleElements({ closedElement: searchWithResult, openedElement: searchWithoutResult })
      }
    }

    document.body.addEventListener('focus', ({ target }) => {
      if (target.matches('#algolia_main_search_form .ais-SearchBox-input')) {
        if (target.value === '') {
          toggleElements({ closedElement: hitsOverlay, openedElement: focusOverlay })
        }
        else if (target.value !== '') {
          toggleElements({ closedElement: focusOverlay, openedElement: hitsOverlay })
        }
        document.getElementById('algolia_main_search_form').classList.add('responsive_search_form_algolia')
        document.querySelector('#algolia_main_search_form .ais-SearchBox-form').classList.add('-focused');
        document.getElementById('darken').style.display = 'block'
      }
    }, true)

    document.body.addEventListener('focusout', (event) => {
      if (event.target.matches('#algolia_main_search_form .ais-SearchBox-input')) {

        const deviceType = "isComputer"

        if (deviceType !== 'isComputer') {
          event.preventDefault()
        }
        else {
          focusOverlay.style.display = 'none'
          hitsOverlay.style.display = 'none'
          document.getElementById('algolia_main_search_form').classList.remove('responsive_search_form_algolia')
          document.getElementById('darken').style.display = 'none'
        }
      }
      document.querySelector('#algolia_main_search_form .ais-SearchBox-form').classList.remove('-focused');
    }, true) 

    document.body.addEventListener('mousedown', (event) => {
      if (event.target.matches('#instant-search-focus, #instant-search-focus *, #algolia-search-hits, #algolia-search-hits *')) {
        event.preventDefault()
      }
    })

    document.body.addEventListener('click', (event) => {
      if (event.target.matches('#mobile_close_search_overlay')) {
        focusOverlay.style.display = 'none'
        hitsOverlay.style.display = 'none'
        document.getElementById('algolia_main_search_form').classList.remove('responsive_search_form_algolia')
        document.getElementById('darken').style.display = 'none'
      }


      if (event.target.matches('#algolia_main_search_form .ais-SearchBox-submit, #algolia_main_search_form .ais-SearchBox-submit *')) {
        const query = document.querySelector('#algolia_main_search_form .ais-SearchBox-input').value

        if (totalResults.sum() > 0) {
          window.location.href = `/search?query=${encodeURI(query)}`
        }
        else {
          event.preventDefault()
        }
      }
      /*
      /* Au clic sur l'icone ajouter au panier dans l'overlay de recherche,
      /* on ajoute la classe added-to-cart
      */
      if ( event.target.matches('.divbox-product-overlay .panier') ) {
        $(event.target).addClass('product_added_to_cart')
        $(event.target).addClass('bounce_animation').delay(1000).queue(function (next) {
            $(this).removeClass('bounce_animation');
            next();
        });
        //event.target.classList.push('product_added_to_cart')
        event.preventDefault()
      }
    })

    document.body.addEventListener('keypress', (event) => {
      if (event.target.matches('#algolia_main_search_form .ais-SearchBox-input') && event.keyCode === 13) {
        if (totalResults.sum() > 0) {
          window.location.href = `/search?query=${encodeURI(event.target.value)}`
        }
        else {
          event.preventDefault()
        }
      }
    })

    let timerId; // for the debounce

    search.addWidgets([
      instantsearch.widgets.configure(
        {
          hitsPerPage: 6,
          filters: 'main=1 AND flags.available=1',
          attributesToRetrieve: ['name', 'brands', 'medias', 'main', 'product_id', 'price', 'origin_price', 'main_category_tree', 'discount', 'committed_price', 'url', 'merchant', 'marketplace', 'quantity', 'attributes', 'internal_reference', 'top','flags','delay'],
          clickAnalytics: true,
        }
      ),
      instantsearch.widgets.searchBox(
        {
          container: '#algolia_main_search_form',
          queryHook(query, refine) {

            if (query === '') {
              toggleElements({ closedElement: hitsOverlay, openedElement: focusOverlay })
              return
            }

            clearTimeout(timerId)
            timerId = setTimeout(() => refine(query), 300)
            toggleElements({ closedElement: focusOverlay, openedElement: hitsOverlay })

          },

          placeholder: 'Recherchez un produit, une marque',
          cssClasses: {
            form: [
              'form-inline',
              'mx-auto',
              'bg-white',
              'rounded-xl',
              'overflow-hidden',
              'd-flex',
              'align-items-center',
              'justify-content-between',
              'w-auto',
            ],

            input: [
              'form-control',
              'font-family-medium',
              'font-size-16',
              'rounded-xl',
              'flex-fill',
              'py-lg-4',
              'px-lg-3',
              'py-2',
              'px-2',
            ],
            submit: [
              'form-control',
              'rounded-xl',
              'mr-1',
              'cursor-pointer'
            ],
            reset: ['form-control', 'rounded-0', 'mr-1', 'my-1', 'cursor-pointer']
          }
        }
      ),

      instantsearch.widgets.hits(
        {
          container: '#hits-products',
          transformItems(items) {
            totalResults.products = items.length
            totalResults.toggleSearchDivs()
            seeAllProductsButton.classList.toggle('d-flex', items.length > 0)
            seeAllProductsButton.classList.toggle('d-none', items.length === 0)
            
            return items.map(({ name, brands = [{}], medias = [{}], main = false, product_id, price, main_category_tree = {}, origin_price, stats = { avis: {} }, marketplace = {}, flags = {}, ...others }) => {
              // double check on the parameters
              if (brands === null || brands[0] === undefined) { brands = [{}]; }
              if (medias === null || medias[0] === undefined) { medias = [{}]; }
              if (stats === null || stats === undefined || stats.avis === null || stats.avis === undefined) { stats = { avis: {} } }
              if (main_category_tree === null) { main_category_tree = {}; }
              if (marketplace === null) { marketplace = {} }

              name = name.replace(/"/g, '&quot;');
              const brand = brands[0].name
              const img = medias[0].src
              price = Number.isInteger(price) ? price : price.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
              origin_price = Number.isInteger(origin_price) ? origin_price : origin_price.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })

              const product_rel = `products_${product_id}__Algolia-Search_${price}`;
              const productJSON = { name, id: product_id, category: main_category_tree.name, brand, price };

              return {
                name,
                brand,
                img,
                main,
                product_id,
                price,
                main_category_tree,
                origin_price,
                stats,
                marketplace,
                product_rel,
                productJSON,
                flags,
                ...others
              }
            });
            
            /*return items.map(({ brands = [{}], medias = [{}], price, ...others }) => {

              price = Number.isInteger(price) ? price : price.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
              return {
                brand: brands[0].name,
                img: medias[0].src,
                price,
                ...others
              }
            })*/
          },

          templates: {
            empty() {
              return `

<div class="container px-0 mx-auto row">	
	<div class="col-12 col-md-9 query_suggestions search-without-results">
        <h4 class="global-search-overlay__title">Produits</h4>
        <p>Nous n’avons pas trouvé de résultat pour votre recherche</p>				
        <h4 class="mb-3 global-search-overlay__title">Ce que cherchent nos clients</h4>
        <div class="d-inline-flex d-md-flex mb-3 algolia-inspiration w-100">
            <a href="/search?query=Savon" class="global-search-overlay__link rounded-xl label-zoom px-3">Savon<span class="pl-2"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M10.9498 4.17588C10.5726 3.91666 10.1479 3.73623 9.70066 3.6452C9.25339 3.55417 8.79259 3.55439 8.3454 3.64583C8.07232 3.71832 7.83863 3.89668 7.69511 4.14213C7.5516 4.38758 7.50987 4.68028 7.57899 4.95662C7.64906 5.21223 7.81031 5.43265 8.03172 5.57546C8.25312 5.71828 8.51904 5.77339 8.77828 5.73019C8.92204 5.71236 9.06741 5.71236 9.21116 5.73019C9.31926 5.76552 9.42381 5.81109 9.52341 5.86629C9.63697 5.9222 9.74611 5.98686 9.84984 6.05968C9.94659 6.14062 10.0368 6.22924 10.1195 6.3247C10.1763 6.41782 10.233 6.5181 10.2827 6.61838C10.3242 6.72486 10.3551 6.83523 10.375 6.94786C10.4471 7.22343 10.6227 7.4598 10.8646 7.60684C11.1092 7.74948 11.3984 7.79301 11.6736 7.7286C11.9447 7.65554 12.1765 7.47809 12.3194 7.23437C12.4514 6.98429 12.494 6.69591 12.44 6.41782C12.3544 5.96196 12.1782 5.52833 11.922 5.14303C11.6659 4.75773 11.3352 4.42874 10.9498 4.17588Z" fill="currentColor"/>
  <path d="M23.5035 15.2638C21.0576 13.9125 18.5999 12.5969 16.1303 11.3172C16.5932 10.1638 16.7891 8.91908 16.703 7.67776C16.6169 6.43645 16.2511 5.23136 15.6336 4.1544C14.629 2.5272 13.1249 1.2762 11.3504 0.592106C9.57595 -0.0919846 7.62854 -0.171635 5.80506 0.3653C4.16033 0.891567 2.70141 1.88654 1.60399 3.2304C0.52215 4.72471 -0.0415287 6.5373 0.000208478 8.38758C0.0241508 10.1639 0.60201 11.8875 1.6517 13.3134C2.7014 14.7394 4.16956 15.7953 5.84764 16.3311C7.32131 16.8389 8.90894 16.9063 10.4196 16.5251C11.9303 16.1439 13.2998 15.3303 14.3633 14.1823C14.446 14.3683 14.592 14.5182 14.7749 14.6049L19.4585 16.8325L20.5656 17.3625C20.9625 17.5772 21.3726 17.7662 21.7932 17.9284C22.0343 18.0022 22.2908 18.0078 22.5348 17.9446C22.7788 17.8815 23.0009 17.752 23.177 17.5702C23.3049 17.4428 23.4148 17.2982 23.5035 17.1405C23.5035 17.1405 23.5389 17.0832 23.5531 17.0474C23.6939 16.944 23.8076 16.8076 23.8844 16.6499C23.9612 16.4922 23.9987 16.318 23.9938 16.1424C23.9889 15.9668 23.9417 15.795 23.8563 15.6419C23.7709 15.4888 23.6498 15.3591 23.5035 15.2638ZM7.50819 14.5476C6.25165 14.3526 5.08134 13.7836 4.14708 12.9133C3.21283 12.043 2.55714 10.9112 2.26396 9.66255C1.92469 8.04778 2.1982 6.3633 3.03037 4.9423C3.81866 3.75952 4.98247 2.88292 6.33019 2.45682C7.54723 2.07696 8.8473 2.06153 10.0729 2.41242C11.2984 2.76331 12.3968 3.46545 13.235 4.43374C14.0372 5.42432 14.5122 6.64336 14.5937 7.92009C14.6751 9.19683 14.3588 10.4673 13.6892 11.5535C13.0943 12.6398 12.1783 13.5115 11.0689 14.047C9.95957 14.5825 8.71226 14.7549 7.5011 14.5404L7.50819 14.5476Z" fill="currentColor"/>
  <path d="M9.20424 11.3315C8.95915 11.1976 8.67462 11.1569 8.40235 11.2169H8.34557C8.1945 11.2307 8.04248 11.2307 7.89141 11.2169C7.7539 11.189 7.61869 11.1507 7.48691 11.1023L7.29531 11.002C7.17355 10.9262 7.03773 10.8763 6.89617 10.8553C6.75462 10.8344 6.61032 10.8429 6.47213 10.8802C6.19964 10.9499 5.96517 11.1248 5.81926 11.3673C5.67513 11.6132 5.63184 11.9062 5.69862 12.1838C5.77466 12.4575 5.94931 12.6925 6.18827 12.8428C7.00729 13.3287 7.97613 13.4895 8.90619 13.2941C9.17823 13.2193 9.41173 13.0425 9.55906 12.7998C9.70151 12.5532 9.74228 12.26 9.6726 11.9833C9.60678 11.7126 9.43869 11.4787 9.20424 11.3315Z" fill="currentColor"/>
</svg>
</span></a>
            <a href="/search?query=Farine" class="global-search-overlay__link rounded-xl label-zoom px-3 ml-2">Farine<span class="pl-2"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M10.9498 4.17588C10.5726 3.91666 10.1479 3.73623 9.70066 3.6452C9.25339 3.55417 8.79259 3.55439 8.3454 3.64583C8.07232 3.71832 7.83863 3.89668 7.69511 4.14213C7.5516 4.38758 7.50987 4.68028 7.57899 4.95662C7.64906 5.21223 7.81031 5.43265 8.03172 5.57546C8.25312 5.71828 8.51904 5.77339 8.77828 5.73019C8.92204 5.71236 9.06741 5.71236 9.21116 5.73019C9.31926 5.76552 9.42381 5.81109 9.52341 5.86629C9.63697 5.9222 9.74611 5.98686 9.84984 6.05968C9.94659 6.14062 10.0368 6.22924 10.1195 6.3247C10.1763 6.41782 10.233 6.5181 10.2827 6.61838C10.3242 6.72486 10.3551 6.83523 10.375 6.94786C10.4471 7.22343 10.6227 7.4598 10.8646 7.60684C11.1092 7.74948 11.3984 7.79301 11.6736 7.7286C11.9447 7.65554 12.1765 7.47809 12.3194 7.23437C12.4514 6.98429 12.494 6.69591 12.44 6.41782C12.3544 5.96196 12.1782 5.52833 11.922 5.14303C11.6659 4.75773 11.3352 4.42874 10.9498 4.17588Z" fill="currentColor"/>
  <path d="M23.5035 15.2638C21.0576 13.9125 18.5999 12.5969 16.1303 11.3172C16.5932 10.1638 16.7891 8.91908 16.703 7.67776C16.6169 6.43645 16.2511 5.23136 15.6336 4.1544C14.629 2.5272 13.1249 1.2762 11.3504 0.592106C9.57595 -0.0919846 7.62854 -0.171635 5.80506 0.3653C4.16033 0.891567 2.70141 1.88654 1.60399 3.2304C0.52215 4.72471 -0.0415287 6.5373 0.000208478 8.38758C0.0241508 10.1639 0.60201 11.8875 1.6517 13.3134C2.7014 14.7394 4.16956 15.7953 5.84764 16.3311C7.32131 16.8389 8.90894 16.9063 10.4196 16.5251C11.9303 16.1439 13.2998 15.3303 14.3633 14.1823C14.446 14.3683 14.592 14.5182 14.7749 14.6049L19.4585 16.8325L20.5656 17.3625C20.9625 17.5772 21.3726 17.7662 21.7932 17.9284C22.0343 18.0022 22.2908 18.0078 22.5348 17.9446C22.7788 17.8815 23.0009 17.752 23.177 17.5702C23.3049 17.4428 23.4148 17.2982 23.5035 17.1405C23.5035 17.1405 23.5389 17.0832 23.5531 17.0474C23.6939 16.944 23.8076 16.8076 23.8844 16.6499C23.9612 16.4922 23.9987 16.318 23.9938 16.1424C23.9889 15.9668 23.9417 15.795 23.8563 15.6419C23.7709 15.4888 23.6498 15.3591 23.5035 15.2638ZM7.50819 14.5476C6.25165 14.3526 5.08134 13.7836 4.14708 12.9133C3.21283 12.043 2.55714 10.9112 2.26396 9.66255C1.92469 8.04778 2.1982 6.3633 3.03037 4.9423C3.81866 3.75952 4.98247 2.88292 6.33019 2.45682C7.54723 2.07696 8.8473 2.06153 10.0729 2.41242C11.2984 2.76331 12.3968 3.46545 13.235 4.43374C14.0372 5.42432 14.5122 6.64336 14.5937 7.92009C14.6751 9.19683 14.3588 10.4673 13.6892 11.5535C13.0943 12.6398 12.1783 13.5115 11.0689 14.047C9.95957 14.5825 8.71226 14.7549 7.5011 14.5404L7.50819 14.5476Z" fill="currentColor"/>
  <path d="M9.20424 11.3315C8.95915 11.1976 8.67462 11.1569 8.40235 11.2169H8.34557C8.1945 11.2307 8.04248 11.2307 7.89141 11.2169C7.7539 11.189 7.61869 11.1507 7.48691 11.1023L7.29531 11.002C7.17355 10.9262 7.03773 10.8763 6.89617 10.8553C6.75462 10.8344 6.61032 10.8429 6.47213 10.8802C6.19964 10.9499 5.96517 11.1248 5.81926 11.3673C5.67513 11.6132 5.63184 11.9062 5.69862 12.1838C5.77466 12.4575 5.94931 12.6925 6.18827 12.8428C7.00729 13.3287 7.97613 13.4895 8.90619 13.2941C9.17823 13.2193 9.41173 13.0425 9.55906 12.7998C9.70151 12.5532 9.74228 12.26 9.6726 11.9833C9.60678 11.7126 9.43869 11.4787 9.20424 11.3315Z" fill="currentColor"/>
</svg>
</span></a>
            <a href="/search?query=Couches" class="global-search-overlay__link rounded-xl label-zoom px-3 ml-2">Couches<span class="pl-2"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M10.9498 4.17588C10.5726 3.91666 10.1479 3.73623 9.70066 3.6452C9.25339 3.55417 8.79259 3.55439 8.3454 3.64583C8.07232 3.71832 7.83863 3.89668 7.69511 4.14213C7.5516 4.38758 7.50987 4.68028 7.57899 4.95662C7.64906 5.21223 7.81031 5.43265 8.03172 5.57546C8.25312 5.71828 8.51904 5.77339 8.77828 5.73019C8.92204 5.71236 9.06741 5.71236 9.21116 5.73019C9.31926 5.76552 9.42381 5.81109 9.52341 5.86629C9.63697 5.9222 9.74611 5.98686 9.84984 6.05968C9.94659 6.14062 10.0368 6.22924 10.1195 6.3247C10.1763 6.41782 10.233 6.5181 10.2827 6.61838C10.3242 6.72486 10.3551 6.83523 10.375 6.94786C10.4471 7.22343 10.6227 7.4598 10.8646 7.60684C11.1092 7.74948 11.3984 7.79301 11.6736 7.7286C11.9447 7.65554 12.1765 7.47809 12.3194 7.23437C12.4514 6.98429 12.494 6.69591 12.44 6.41782C12.3544 5.96196 12.1782 5.52833 11.922 5.14303C11.6659 4.75773 11.3352 4.42874 10.9498 4.17588Z" fill="currentColor"/>
  <path d="M23.5035 15.2638C21.0576 13.9125 18.5999 12.5969 16.1303 11.3172C16.5932 10.1638 16.7891 8.91908 16.703 7.67776C16.6169 6.43645 16.2511 5.23136 15.6336 4.1544C14.629 2.5272 13.1249 1.2762 11.3504 0.592106C9.57595 -0.0919846 7.62854 -0.171635 5.80506 0.3653C4.16033 0.891567 2.70141 1.88654 1.60399 3.2304C0.52215 4.72471 -0.0415287 6.5373 0.000208478 8.38758C0.0241508 10.1639 0.60201 11.8875 1.6517 13.3134C2.7014 14.7394 4.16956 15.7953 5.84764 16.3311C7.32131 16.8389 8.90894 16.9063 10.4196 16.5251C11.9303 16.1439 13.2998 15.3303 14.3633 14.1823C14.446 14.3683 14.592 14.5182 14.7749 14.6049L19.4585 16.8325L20.5656 17.3625C20.9625 17.5772 21.3726 17.7662 21.7932 17.9284C22.0343 18.0022 22.2908 18.0078 22.5348 17.9446C22.7788 17.8815 23.0009 17.752 23.177 17.5702C23.3049 17.4428 23.4148 17.2982 23.5035 17.1405C23.5035 17.1405 23.5389 17.0832 23.5531 17.0474C23.6939 16.944 23.8076 16.8076 23.8844 16.6499C23.9612 16.4922 23.9987 16.318 23.9938 16.1424C23.9889 15.9668 23.9417 15.795 23.8563 15.6419C23.7709 15.4888 23.6498 15.3591 23.5035 15.2638ZM7.50819 14.5476C6.25165 14.3526 5.08134 13.7836 4.14708 12.9133C3.21283 12.043 2.55714 10.9112 2.26396 9.66255C1.92469 8.04778 2.1982 6.3633 3.03037 4.9423C3.81866 3.75952 4.98247 2.88292 6.33019 2.45682C7.54723 2.07696 8.8473 2.06153 10.0729 2.41242C11.2984 2.76331 12.3968 3.46545 13.235 4.43374C14.0372 5.42432 14.5122 6.64336 14.5937 7.92009C14.6751 9.19683 14.3588 10.4673 13.6892 11.5535C13.0943 12.6398 12.1783 13.5115 11.0689 14.047C9.95957 14.5825 8.71226 14.7549 7.5011 14.5404L7.50819 14.5476Z" fill="currentColor"/>
  <path d="M9.20424 11.3315C8.95915 11.1976 8.67462 11.1569 8.40235 11.2169H8.34557C8.1945 11.2307 8.04248 11.2307 7.89141 11.2169C7.7539 11.189 7.61869 11.1507 7.48691 11.1023L7.29531 11.002C7.17355 10.9262 7.03773 10.8763 6.89617 10.8553C6.75462 10.8344 6.61032 10.8429 6.47213 10.8802C6.19964 10.9499 5.96517 11.1248 5.81926 11.3673C5.67513 11.6132 5.63184 11.9062 5.69862 12.1838C5.77466 12.4575 5.94931 12.6925 6.18827 12.8428C7.00729 13.3287 7.97613 13.4895 8.90619 13.2941C9.17823 13.2193 9.41173 13.0425 9.55906 12.7998C9.70151 12.5532 9.74228 12.26 9.6726 11.9833C9.60678 11.7126 9.43869 11.4787 9.20424 11.3315Z" fill="currentColor"/>
</svg>
</span></a>
            <a href="/search?query=Gourdes" class="global-search-overlay__link rounded-xl label-zoom px-3 ml-2">Gourdes<span class="pl-2"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M10.9498 4.17588C10.5726 3.91666 10.1479 3.73623 9.70066 3.6452C9.25339 3.55417 8.79259 3.55439 8.3454 3.64583C8.07232 3.71832 7.83863 3.89668 7.69511 4.14213C7.5516 4.38758 7.50987 4.68028 7.57899 4.95662C7.64906 5.21223 7.81031 5.43265 8.03172 5.57546C8.25312 5.71828 8.51904 5.77339 8.77828 5.73019C8.92204 5.71236 9.06741 5.71236 9.21116 5.73019C9.31926 5.76552 9.42381 5.81109 9.52341 5.86629C9.63697 5.9222 9.74611 5.98686 9.84984 6.05968C9.94659 6.14062 10.0368 6.22924 10.1195 6.3247C10.1763 6.41782 10.233 6.5181 10.2827 6.61838C10.3242 6.72486 10.3551 6.83523 10.375 6.94786C10.4471 7.22343 10.6227 7.4598 10.8646 7.60684C11.1092 7.74948 11.3984 7.79301 11.6736 7.7286C11.9447 7.65554 12.1765 7.47809 12.3194 7.23437C12.4514 6.98429 12.494 6.69591 12.44 6.41782C12.3544 5.96196 12.1782 5.52833 11.922 5.14303C11.6659 4.75773 11.3352 4.42874 10.9498 4.17588Z" fill="currentColor"/>
  <path d="M23.5035 15.2638C21.0576 13.9125 18.5999 12.5969 16.1303 11.3172C16.5932 10.1638 16.7891 8.91908 16.703 7.67776C16.6169 6.43645 16.2511 5.23136 15.6336 4.1544C14.629 2.5272 13.1249 1.2762 11.3504 0.592106C9.57595 -0.0919846 7.62854 -0.171635 5.80506 0.3653C4.16033 0.891567 2.70141 1.88654 1.60399 3.2304C0.52215 4.72471 -0.0415287 6.5373 0.000208478 8.38758C0.0241508 10.1639 0.60201 11.8875 1.6517 13.3134C2.7014 14.7394 4.16956 15.7953 5.84764 16.3311C7.32131 16.8389 8.90894 16.9063 10.4196 16.5251C11.9303 16.1439 13.2998 15.3303 14.3633 14.1823C14.446 14.3683 14.592 14.5182 14.7749 14.6049L19.4585 16.8325L20.5656 17.3625C20.9625 17.5772 21.3726 17.7662 21.7932 17.9284C22.0343 18.0022 22.2908 18.0078 22.5348 17.9446C22.7788 17.8815 23.0009 17.752 23.177 17.5702C23.3049 17.4428 23.4148 17.2982 23.5035 17.1405C23.5035 17.1405 23.5389 17.0832 23.5531 17.0474C23.6939 16.944 23.8076 16.8076 23.8844 16.6499C23.9612 16.4922 23.9987 16.318 23.9938 16.1424C23.9889 15.9668 23.9417 15.795 23.8563 15.6419C23.7709 15.4888 23.6498 15.3591 23.5035 15.2638ZM7.50819 14.5476C6.25165 14.3526 5.08134 13.7836 4.14708 12.9133C3.21283 12.043 2.55714 10.9112 2.26396 9.66255C1.92469 8.04778 2.1982 6.3633 3.03037 4.9423C3.81866 3.75952 4.98247 2.88292 6.33019 2.45682C7.54723 2.07696 8.8473 2.06153 10.0729 2.41242C11.2984 2.76331 12.3968 3.46545 13.235 4.43374C14.0372 5.42432 14.5122 6.64336 14.5937 7.92009C14.6751 9.19683 14.3588 10.4673 13.6892 11.5535C13.0943 12.6398 12.1783 13.5115 11.0689 14.047C9.95957 14.5825 8.71226 14.7549 7.5011 14.5404L7.50819 14.5476Z" fill="currentColor"/>
  <path d="M9.20424 11.3315C8.95915 11.1976 8.67462 11.1569 8.40235 11.2169H8.34557C8.1945 11.2307 8.04248 11.2307 7.89141 11.2169C7.7539 11.189 7.61869 11.1507 7.48691 11.1023L7.29531 11.002C7.17355 10.9262 7.03773 10.8763 6.89617 10.8553C6.75462 10.8344 6.61032 10.8429 6.47213 10.8802C6.19964 10.9499 5.96517 11.1248 5.81926 11.3673C5.67513 11.6132 5.63184 11.9062 5.69862 12.1838C5.77466 12.4575 5.94931 12.6925 6.18827 12.8428C7.00729 13.3287 7.97613 13.4895 8.90619 13.2941C9.17823 13.2193 9.41173 13.0425 9.55906 12.7998C9.70151 12.5532 9.74228 12.26 9.6726 11.9833C9.60678 11.7126 9.43869 11.4787 9.20424 11.3315Z" fill="currentColor"/>
</svg>
</span></a>
        </div>
	</div>`},
            item(item) {
              item.isSearch = true;
              item.position = item.__hitIndex;
              item.productJSON.position = item.position;
              item.productJSON = encodeURIComponent(JSON.stringify(item.productJSON));

              return `<div class="divbox-product-overlay">
  <a href="${item.url}?objectID=${item.objectID}&queryID=${ item.__queryID }&isSearch=true"
     data-attribute-index="offer"
     title="Page du produit &quot;${item.name}&quot;"
     data-insights-object-id="${item.objectID}"
     data-insights-position="${item.__position}"
     data-insights-query-id="${item.__queryID}"
     data-insights-is-search="true"
     >
    <div class="wrapper d-flex align-items-start mb-3 mb-lg-0 position-relative h-100">
      <div class="img-container">
        <img class="mx-0 px-1 img-fluid" src="${item.img}"/>
      </div>
      <div class="item-details text-left">
        <div>
         <p class="item-details__brand">${item.brand}
        </p>
        <p class="item-details__name">${item.name}
        </p>
        </div>
        
        <div class="d-flex justify-content-between">
          <p class="item-details__price">${item.price} €
          </p>
                                                            <div
               class="panier rounded-xl position-relative product_to_cart"
               rel="${ item.product_rel }"
               data-isvariante="0"
               data-products-stock="${item.quantity}"
               data-refinterne="${item.internal_reference}"
               data-brand="${item.brand}"
               data-name="${item.name}"
               data-category="${item.main_category_tree.name}"
               data-categories-id="${item.main_category_tree.id}"
               data-position="${item.position}"
               data-productsurl="${item.url}"
               data-origin="overlay-algolia" 
               data-product-json="${item.productJSON}"
               data-merchants-id="${ item.merchant.id}"
               data-merchants-name="${ item.merchant.name }"
               data-expedition-delay="${ item.delivery ? (item.delivery.standard || {}).min_shipping_delay : '' }"
               data-insights-object-id="${item.objectID}"
               data-insights-query-id="${item.__queryID}"
               data-min-shipping-price="${ item.merchant.free_shipping_std }"
               data-mirakl-offers-id="${ item.marketplace.product_reference }"
               data-insights-is-search="true"
              >
            <div
               class="d-flex panier__icon"
               name="Ajout_Panier"
               data-insights-object-id="${item.objectID}"
               data-insights-position="${item.__position}"
               data-insights-query-id="${item.__queryID}"
               data-insights-is-search="true">
              <svg  width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M41.2829 16.7981C40.2201 16.9295 39.1318 16.9294 38.0689 17.0608H37.9407H37.6078C35.4317 17.5299 33.2219 17.8154 31.0004 17.9145C32.5701 17.0049 33.9326 15.7624 34.9969 14.2699C36.0612 12.7774 36.8029 11.0692 37.1725 9.25933C37.3325 8.36726 37.3712 7.45675 37.2877 6.55376C37.1827 5.39172 36.8159 4.27063 36.2158 3.27908C35.6158 2.28754 34.799 1.45272 33.8303 0.840575C32.5811 0.222062 31.2067 -0.0837695 29.8199 -0.0518061C28.4331 -0.0198427 27.0735 0.348998 25.8527 1.02444C22.9075 2.47202 20.5574 4.94282 19.2195 7.99849C17.8279 5.47943 15.8083 3.38525 13.3676 1.93068C11.9305 0.93367 10.1722 0.547576 8.46306 0.853706C7.4003 1.23078 6.47995 1.94082 5.83198 2.8835C5.18401 3.82619 4.84125 4.95387 4.85205 6.10722C4.91181 8.40421 5.69329 10.62 7.08015 12.4246C8.47138 14.421 10.2074 16.1393 12.2022 17.4942C8.84727 17.7437 5.50513 18.256 2.15016 18.8076H2.03496C1.75038 18.7886 1.46523 18.8353 1.20055 18.9442C0.935876 19.0531 0.698292 19.2214 0.505423 19.4369C0.312553 19.6523 0.169274 19.9094 0.0860321 20.1892C0.00279016 20.4689 -0.0182651 20.7643 0.0244444 21.0535C1.23737 28.0843 3.11583 34.9772 5.63315 41.6341C5.74139 42.0068 5.96214 42.3348 6.26356 42.5708C6.56498 42.8068 6.93161 42.9387 7.31071 42.9475C17.4397 42.409 27.5814 43.0394 37.7103 42.5009H37.8512H38.0048H38.1457C38.4533 42.4457 38.7383 42.2994 38.9659 42.08C39.1934 41.8606 39.3534 41.5778 39.4262 41.2663C40.7067 33.8852 41.744 26.4777 43.0117 19.0965C43.1142 18.059 42.5122 16.6142 41.2829 16.7981ZM36.1608 39.0205C33.9967 39.1124 31.8327 39.1649 29.6814 39.1912L30.7186 30.1289C33.0619 29.8268 35.4181 29.4722 37.7615 29.1045C37.3005 32.3748 36.7755 35.7239 36.2249 39.0205H36.1608ZM3.69954 22.183C5.68436 21.8546 7.65637 21.5657 9.62838 21.3687C9.64203 21.5291 9.67216 21.6875 9.7181 21.8415C10.3968 23.9954 10.9986 26.1756 11.5876 28.4084C9.44914 28.5397 7.29786 28.6448 5.18499 28.7236C4.65571 26.5434 4.18194 24.3632 3.76363 22.183H3.69954ZM13.1498 21.0797C15.7622 20.9293 18.382 20.982 20.9866 21.2373C23.3768 21.5028 25.779 21.6387 28.1832 21.6445C27.9911 23.4044 27.7862 25.1512 27.5814 26.898C23.441 27.3971 19.3007 27.7998 15.1603 28.1063C14.5969 25.8079 13.9566 23.4044 13.2139 21.0797H13.1498ZM31.7046 21.5394C33.8998 21.3972 36.0822 21.0944 38.2352 20.6332H39.1317C38.8628 22.2355 38.5939 23.851 38.3378 25.4664C35.9475 25.8604 33.5444 26.2194 31.1284 26.5434L31.7046 21.5394ZM6.09412 32.2434C8.21979 32.1515 10.3583 32.0464 12.4967 31.9151C13.1114 34.3405 13.7176 36.7702 14.3151 39.2043C12.3559 39.2043 10.384 39.2043 8.42476 39.3619C7.68205 37.011 6.90095 34.6338 6.19666 32.2434H6.09412ZM17.9133 39.1781L16.031 31.6524C19.7317 31.3766 23.4325 31.0089 27.1204 30.5754C26.7874 33.4517 26.4673 36.328 26.1344 39.2043L17.9133 39.1781Z" fill="currentColor"/>
</svg>
            </div>
          </div>
        </div>
      </div>
    </div >
  </a>
</div>`;
            }
          },
          cssClasses: {
            list: ['algolia-product-list'],
            item: ['algolia-item']
          }
        }
      ),
      instantsearch.widgets.index(
        { indexName: 'dev_ev' }
      ).addWidgets(
        [
          instantsearch.widgets.configure(
            {
              hitsPerPage: 6,
              attributesToRetrieve: ['name', 'url'],
              filters: ''
            }
          ),
          instantsearch.widgets.hits(
            {
              container: '#hits-brands',
              transformItems(items) {

                totalResults.brands = items.length
                totalResults.toggleSearchDivs()
                return items
              },
              templates: {
                empty(results) {
                   return `<p>Aucune marque nommée <span class="font-family-sb">${results.query}</span></p>`;
                },
                item(item) {
                  return `<a href="${item.url}?objectID=${ item.objectID }&queryID=${ item.__queryID }&searchIndex=brand&isSearch=true"
                      data-attribute-index="brand"
                      title="Page de la marque &quot;${ item.name }&quot;"
                      data-insights-object-id="${item.objectID}"
                      data-insights-position="${item.__position}"
                      data-insights-query-id="${item.__queryID}"
                      data-insights-is-search="true">
                    ${instantsearch.highlight({ attribute: 'name', hit: item, highlightedTagName: 'span class="font-family-sb"' })}
                  </a >`
                }
              }
            }
          ),
        ]
      ),
      instantsearch.widgets.index(
        { indexName: 'dev_ev' }
      ).addWidgets(
        [
          instantsearch.widgets.configure(
            {
              hitsPerPage: 6,
              filters: 'show_search=1',
              attributesToRetrieve: ['name', 'url'],
            }
          ),
          instantsearch.widgets.hits(
            {
              container: '#hits-categories',
              transformItems(items) {
                totalResults.categories = items.length
                totalResults.toggleSearchDivs()
                return items
              },
              templates: {
                empty(results) {
                   return `<p>Aucune catégorie nommée <span class="font-family-sb">${results.query}</span></p>`;
                },
                item(item) {
                  return `<a href="${item.url}?objectID=${ item.objectID }&queryID=${ item.__queryID }&searchIndex=category&isSearch=true"
                      data-attribute-index="category"
                      title="Page de la catégorie &quot;${ item.name }&quot;"
                      data-insights-object-id="${item.objectID}"
                      data-insights-position="${item.__position}"
                      data-insights-query-id="${item.__queryID}"
                      data-insights-is-search="true">
                    ${instantsearch.highlight({ attribute: 'name', hit: item, highlightedTagName: 'span class="font-family-sb"' })}
                  </a>`
                }
              }
            }
          ),
        ]
      ),
    ]);

    search.start()
  })
</script>

<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="/">{{  __('messages.home') }}</a></li>
            <li><a style="color: #BCCC32;" href="javascript:void(0);">{{  __('messages.vendors') }} </a></li>

        </ul>
    </div>
</div>
<div id="pageContent">
	<div class="container offset-0">
		<div class="row">
            <div id="algolia_main_search_form">
                <button id="mobile_close_search_overlay" class="btn btn-primary fa fa-arrow-left"></button>
            </div>



<div class="global-search-overlay">
	<div id="instant-search-focus" class="fadein-0-8s">
		<div class="container px-0 mx-auto row d-flex flex-column flex-md-row">
			<div
				class="col-md-12 col-lg-3 mb-3 mb-md-0 position-relative">
								<span class="d-none d-lg-block"><img class="mx-auto mb-2 fadein-0-8s lazyload img-fluid" data-src="https://static.greenweez.com/assets/static/store-1/french/images/algolia/gwzMarqueDesktop.jpg"></span>
				<span class="d-block d-lg-none"><img class="mx-auto mb-2 fadein-0-8s lazyload img-fluid" data-src="https://static.greenweez.com/assets/static/store-1/french/images/algolia/gwzMarqueMobile.jpg"></span>
                <a class="full_block_link" alt="prix engages" href="/greenweez-m13678"></a>  
			</div>
			<div class="col-md-12 col-lg-9">
				<section class="row mx-auto d-flex flex-column justify-content-start justify-content-lg-between h-100">
					<div class="query_suggestions w-100">
						<h4 class="mb-3 global-search-overlay__title">Ce que cherchent nos clients</h4>
						<div class="d-inline-flex d-md-flex mb-3 algolia-inspiration w-100">
							<a href=" " class="global-search-overlay__link  rounded-xl label-zoom px-3 d-flex">Savon<span class="pl-2 d-flex align-items-center"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
										  <path d="M10.9498 4.17588C10.5726 3.91666 10.1479 3.73623 9.70066 3.6452C9.25339 3.55417 8.79259 3.55439 8.3454 3.64583C8.07232 3.71832 7.83863 3.89668 7.69511 4.14213C7.5516 4.38758 7.50987 4.68028 7.57899 4.95662C7.64906 5.21223 7.81031 5.43265 8.03172 5.57546C8.25312 5.71828 8.51904 5.77339 8.77828 5.73019C8.92204 5.71236 9.06741 5.71236 9.21116 5.73019C9.31926 5.76552 9.42381 5.81109 9.52341 5.86629C9.63697 5.9222 9.74611 5.98686 9.84984 6.05968C9.94659 6.14062 10.0368 6.22924 10.1195 6.3247C10.1763 6.41782 10.233 6.5181 10.2827 6.61838C10.3242 6.72486 10.3551 6.83523 10.375 6.94786C10.4471 7.22343 10.6227 7.4598 10.8646 7.60684C11.1092 7.74948 11.3984 7.79301 11.6736 7.7286C11.9447 7.65554 12.1765 7.47809 12.3194 7.23437C12.4514 6.98429 12.494 6.69591 12.44 6.41782C12.3544 5.96196 12.1782 5.52833 11.922 5.14303C11.6659 4.75773 11.3352 4.42874 10.9498 4.17588Z" fill="currentColor"/>
										  <path d="M23.5035 15.2638C21.0576 13.9125 18.5999 12.5969 16.1303 11.3172C16.5932 10.1638 16.7891 8.91908 16.703 7.67776C16.6169 6.43645 16.2511 5.23136 15.6336 4.1544C14.629 2.5272 13.1249 1.2762 11.3504 0.592106C9.57595 -0.0919846 7.62854 -0.171635 5.80506 0.3653C4.16033 0.891567 2.70141 1.88654 1.60399 3.2304C0.52215 4.72471 -0.0415287 6.5373 0.000208478 8.38758C0.0241508 10.1639 0.60201 11.8875 1.6517 13.3134C2.7014 14.7394 4.16956 15.7953 5.84764 16.3311C7.32131 16.8389 8.90894 16.9063 10.4196 16.5251C11.9303 16.1439 13.2998 15.3303 14.3633 14.1823C14.446 14.3683 14.592 14.5182 14.7749 14.6049L19.4585 16.8325L20.5656 17.3625C20.9625 17.5772 21.3726 17.7662 21.7932 17.9284C22.0343 18.0022 22.2908 18.0078 22.5348 17.9446C22.7788 17.8815 23.0009 17.752 23.177 17.5702C23.3049 17.4428 23.4148 17.2982 23.5035 17.1405C23.5035 17.1405 23.5389 17.0832 23.5531 17.0474C23.6939 16.944 23.8076 16.8076 23.8844 16.6499C23.9612 16.4922 23.9987 16.318 23.9938 16.1424C23.9889 15.9668 23.9417 15.795 23.8563 15.6419C23.7709 15.4888 23.6498 15.3591 23.5035 15.2638ZM7.50819 14.5476C6.25165 14.3526 5.08134 13.7836 4.14708 12.9133C3.21283 12.043 2.55714 10.9112 2.26396 9.66255C1.92469 8.04778 2.1982 6.3633 3.03037 4.9423C3.81866 3.75952 4.98247 2.88292 6.33019 2.45682C7.54723 2.07696 8.8473 2.06153 10.0729 2.41242C11.2984 2.76331 12.3968 3.46545 13.235 4.43374C14.0372 5.42432 14.5122 6.64336 14.5937 7.92009C14.6751 9.19683 14.3588 10.4673 13.6892 11.5535C13.0943 12.6398 12.1783 13.5115 11.0689 14.047C9.95957 14.5825 8.71226 14.7549 7.5011 14.5404L7.50819 14.5476Z" fill="currentColor"/>
										  <path d="M9.20424 11.3315C8.95915 11.1976 8.67462 11.1569 8.40235 11.2169H8.34557C8.1945 11.2307 8.04248 11.2307 7.89141 11.2169C7.7539 11.189 7.61869 11.1507 7.48691 11.1023L7.29531 11.002C7.17355 10.9262 7.03773 10.8763 6.89617 10.8553C6.75462 10.8344 6.61032 10.8429 6.47213 10.8802C6.19964 10.9499 5.96517 11.1248 5.81926 11.3673C5.67513 11.6132 5.63184 11.9062 5.69862 12.1838C5.77466 12.4575 5.94931 12.6925 6.18827 12.8428C7.00729 13.3287 7.97613 13.4895 8.90619 13.2941C9.17823 13.2193 9.41173 13.0425 9.55906 12.7998C9.70151 12.5532 9.74228 12.26 9.6726 11.9833C9.60678 11.7126 9.43869 11.4787 9.20424 11.3315Z" fill="currentColor"/>
										</svg>
										</span>
							</a>
 
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 col-lg-auto mb-3 mb-md-0">
							<div class="d-flex flex-column">
								<h4 class="global-search-overlay__title">Accès rapide</h4>
								<a class="global-search-overlay__link-pages" href="/informations-livraison" title="Frais de port">Frais de port et livraisons</a>
								<a class="global-search-overlay__link-pages" href="/landing_page_mp_client.php" title="Marketplace">Qu’est ce que la Marketplace ?</a>
							</div>
						</div>
						<div class="col-md-12 col-lg-auto mb-3 mb-md-0">
							<div class="d-flex flex-column">
								<h4 class="global-search-overlay__title">Suggestions</h4>
								<a class="global-search-overlay__link-pages" href="/promos-du-mois-c6179-promotions" title="promotions">Voir nos promotions</a>
								<a class="global-search-overlay__link-pages" href="/zero-dechet-c5397" title="zéro déchet">Zéro Déchet</a>
								<a class="global-search-overlay__link-pages" href="/prix-engages-2-c5306 " title="prix engagés">Les prix engagés</a>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div id="algolia-search-hits">
		<div id="search-with-result">
			<div class="container px-0 mx-auto row">
				<div class="col-12 col-md-12 col-lg-3">
					<div id="brands">
						<div class="brands">
							<h4 class="global-search-overlay__title mb-3">Marques</h4>
							<div id="hits-brands"></div>
						</div>
						<div class="categories">
							<h4 class="global-search-overlay__title mb-3">Catégories</h4>
							<div id="hits-categories"></div>
						</div>
					</div>
				</div>

				<div
					class="col-12 col-lg-9 m-0 pr-3 pr-lg-0" id="algolia_search_main">
										<div id="hits-products" class="mt-3"></div>
					<button id="see-all-products-button" type="button" class="btn btn-primary px-3 rounded-xl py-2 text-center mx-auto mt-2 mb-5">
						<a id="see-all-products-link" href="" alt="" class="d-flex align-items-center justify-content-center">
							Voir tous les produits
							<span class="pl-2 d-flex align-items-center"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M10.9498 4.17588C10.5726 3.91666 10.1479 3.73623 9.70066 3.6452C9.25339 3.55417 8.79259 3.55439 8.3454 3.64583C8.07232 3.71832 7.83863 3.89668 7.69511 4.14213C7.5516 4.38758 7.50987 4.68028 7.57899 4.95662C7.64906 5.21223 7.81031 5.43265 8.03172 5.57546C8.25312 5.71828 8.51904 5.77339 8.77828 5.73019C8.92204 5.71236 9.06741 5.71236 9.21116 5.73019C9.31926 5.76552 9.42381 5.81109 9.52341 5.86629C9.63697 5.9222 9.74611 5.98686 9.84984 6.05968C9.94659 6.14062 10.0368 6.22924 10.1195 6.3247C10.1763 6.41782 10.233 6.5181 10.2827 6.61838C10.3242 6.72486 10.3551 6.83523 10.375 6.94786C10.4471 7.22343 10.6227 7.4598 10.8646 7.60684C11.1092 7.74948 11.3984 7.79301 11.6736 7.7286C11.9447 7.65554 12.1765 7.47809 12.3194 7.23437C12.4514 6.98429 12.494 6.69591 12.44 6.41782C12.3544 5.96196 12.1782 5.52833 11.922 5.14303C11.6659 4.75773 11.3352 4.42874 10.9498 4.17588Z" fill="currentColor"/>
  <path d="M23.5035 15.2638C21.0576 13.9125 18.5999 12.5969 16.1303 11.3172C16.5932 10.1638 16.7891 8.91908 16.703 7.67776C16.6169 6.43645 16.2511 5.23136 15.6336 4.1544C14.629 2.5272 13.1249 1.2762 11.3504 0.592106C9.57595 -0.0919846 7.62854 -0.171635 5.80506 0.3653C4.16033 0.891567 2.70141 1.88654 1.60399 3.2304C0.52215 4.72471 -0.0415287 6.5373 0.000208478 8.38758C0.0241508 10.1639 0.60201 11.8875 1.6517 13.3134C2.7014 14.7394 4.16956 15.7953 5.84764 16.3311C7.32131 16.8389 8.90894 16.9063 10.4196 16.5251C11.9303 16.1439 13.2998 15.3303 14.3633 14.1823C14.446 14.3683 14.592 14.5182 14.7749 14.6049L19.4585 16.8325L20.5656 17.3625C20.9625 17.5772 21.3726 17.7662 21.7932 17.9284C22.0343 18.0022 22.2908 18.0078 22.5348 17.9446C22.7788 17.8815 23.0009 17.752 23.177 17.5702C23.3049 17.4428 23.4148 17.2982 23.5035 17.1405C23.5035 17.1405 23.5389 17.0832 23.5531 17.0474C23.6939 16.944 23.8076 16.8076 23.8844 16.6499C23.9612 16.4922 23.9987 16.318 23.9938 16.1424C23.9889 15.9668 23.9417 15.795 23.8563 15.6419C23.7709 15.4888 23.6498 15.3591 23.5035 15.2638ZM7.50819 14.5476C6.25165 14.3526 5.08134 13.7836 4.14708 12.9133C3.21283 12.043 2.55714 10.9112 2.26396 9.66255C1.92469 8.04778 2.1982 6.3633 3.03037 4.9423C3.81866 3.75952 4.98247 2.88292 6.33019 2.45682C7.54723 2.07696 8.8473 2.06153 10.0729 2.41242C11.2984 2.76331 12.3968 3.46545 13.235 4.43374C14.0372 5.42432 14.5122 6.64336 14.5937 7.92009C14.6751 9.19683 14.3588 10.4673 13.6892 11.5535C13.0943 12.6398 12.1783 13.5115 11.0689 14.047C9.95957 14.5825 8.71226 14.7549 7.5011 14.5404L7.50819 14.5476Z" fill="currentColor"/>
  <path d="M9.20424 11.3315C8.95915 11.1976 8.67462 11.1569 8.40235 11.2169H8.34557C8.1945 11.2307 8.04248 11.2307 7.89141 11.2169C7.7539 11.189 7.61869 11.1507 7.48691 11.1023L7.29531 11.002C7.17355 10.9262 7.03773 10.8763 6.89617 10.8553C6.75462 10.8344 6.61032 10.8429 6.47213 10.8802C6.19964 10.9499 5.96517 11.1248 5.81926 11.3673C5.67513 11.6132 5.63184 11.9062 5.69862 12.1838C5.77466 12.4575 5.94931 12.6925 6.18827 12.8428C7.00729 13.3287 7.97613 13.4895 8.90619 13.2941C9.17823 13.2193 9.41173 13.0425 9.55906 12.7998C9.70151 12.5532 9.74228 12.26 9.6726 11.9833C9.60678 11.7126 9.43869 11.4787 9.20424 11.3315Z" fill="currentColor"/>
</svg>
</span>
						</a>
					</button>
										<h4 class="mb-3 global-search-overlay__title">Inspirez-moi</h4>
					<div class="container algolia-list px-0">
						<div>
							<div class="mb-3 d-inline-flex d-lg-flex justify-content-start align-items-center p-2 position-relative">
									<picture class="mr-2 p-0 rounded-circle guide-picture-bubble-large position-relative" style="width: 50px; height: 50px; flex-basis: 50px; flex-grow: 0; flex-shrink: 0;">
											<img alt="Comment adopter une démarche zéro déchet ?" title="Comment adopter une démarche zéro déchet ?" class="position-absolute lazyload" data-src="https://static.greenweez.com/includes/languages/french/upload/images/categorie_special/zd.jpg">
									</picture>
									<span class="inspirations__text text-left font-size-13">Atteindre le zéro déchet, est-ce possible ?</span>
									<a class="full_block_link" href="/comment-adopter-une-demarche-zero-dechet-g6 " title="Zéro déchet"></a>
							</div>
						</div>
						<div>
							<div class="mb-3 d-inline-flex d-lg-flex justify-content-start align-items-center p-2 position-relative">
									<picture class="mr-2 p-0 rounded-circle guide-picture-bubble-large position-relative" style="width: 50px; height: 50px; flex-basis: 50px; flex-grow: 0; flex-shrink: 0;">
											<img alt="DIY activités à faire avec les enfants" title="DIY activités à faire avec les enfants" class="position-absolute lazyload" data-src="https://static.greenweez.com/includes/languages/french/upload/images/categorie_special/shutterstock_1591296607 (1).jpg">
									</picture>
									<span class="inspirations__text text-left font-size-13">DIY & activités à faire avec les enfants</span>
									<a class="full_block_link" href="/diy-activites-a-faire-avec-les-enfants-g31" title="DIY activités à faire avec les enfants"></a>
							</div>
						</div>
						<div>
							<div class="mb-3 d-inline-flex d-lg-flex justify-content-start align-items-center p-2 position-relative">
									<picture class="mr-2 p-0 rounded-circle guide-picture-bubble-large position-relative" style="width: 50px; height: 50px; flex-basis: 50px; flex-grow: 0; flex-shrink: 0;">
											<img alt="Nos petites marques françaises" title="Nos petites marques françaises" class="position-absolute lazyload" data-src="https://static.greenweez.com/includes/languages/french/upload/images/categorie_special/bloc1_marques_fran__aises.jpg">
									</picture>
									<span class="inspirations__text text-left font-size-13">Nos petites marques françaises</span>
									<a class="full_block_link" href="/nos-petites-marques-francaises-g12" title="Nos petites marques françaises"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			<div class="search-without-result" id="search-without-result">
				<div class="container">
					<div class="mx-auto row d-flex flex-column flex-md-row">
						<div class="col-12 col-md-3 d-flex align-items-center">
							<span class="pencil-icon">
								<svg viewBox="0 0 158 98" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M156.061 18.8127C152.251 12.0612 146.41 4.5773 139.177 1.21299C137.418 0.307332 135.448 -0.108782 133.474 0.00794351C131.5 0.124669 129.593 0.769969 127.952 1.87669C122.111 5.9276 116.726 11.0313 111.296 15.6086C98.5648 26.3881 86.1986 37.6483 74.2203 49.206L73.9008 49.5722C73.5178 50.0342 73.2796 50.5996 73.2164 51.1971C69.9765 62.6709 66.7519 74.1141 63.5424 85.5268C57.8203 87.5585 51.5674 87.5018 45.8829 85.3666C52.0888 80.1027 56.4467 73.0079 55.3059 64.2195C55.002 61.4479 53.9823 58.8038 52.3475 56.5486C50.7127 54.2935 48.5194 52.5054 45.9844 51.3612C43.4495 50.2169 40.6608 49.7561 37.8939 50.0244C35.1271 50.2927 32.4779 51.2807 30.2084 52.8907C19.3024 60.7637 23.3636 75.5026 31.3492 83.879C31.8968 84.4512 32.4672 85.0005 33.0376 85.5268C25.0064 89.6922 13.1421 94.8645 5.81823 87.8155C2.62401 84.7716 -2.21296 89.6006 0.981267 92.6674C11.18 102.417 25.6224 96.787 36.5056 91.546C37.4867 91.0654 38.4449 90.539 39.4032 89.9897C44.1831 92.5153 49.5042 93.8351 54.9066 93.8351C60.309 93.8351 65.6302 92.5153 70.41 89.9897L70.7751 89.7609C81.0118 86.9534 91.2257 84.0087 101.417 80.9267C101.708 80.8197 101.99 80.6896 102.261 80.5376L102.444 80.4003L102.672 80.1027L102.831 79.9883C113.783 69.7581 124.529 59.3905 135.367 48.9542C140.865 43.7132 146.387 38.4951 151.977 33.3685C154.294 31.7133 156.199 29.5438 157.544 27.029C157.922 25.6351 157.985 24.1741 157.728 22.7526C157.472 21.3312 156.902 19.9851 156.061 18.8127V18.8127ZM31.6914 61.1985C32.6797 59.548 34.1617 58.252 35.9265 57.495C37.6913 56.7379 39.6491 56.5582 41.5215 56.9815C43.394 57.4048 45.0857 58.4096 46.3562 59.8528C47.6266 61.296 48.4111 63.1043 48.598 65.0205C49.6019 72.2756 44.9247 77.9056 39.4717 81.8192C33.4483 77.2419 27.3792 68.2933 31.6914 61.1985ZM141.892 12.8622C142.115 12.6568 142.314 12.4264 142.485 12.1756C143.657 13.3959 144.761 14.6795 145.794 16.0206L94.2298 63.6474L89.4613 58.5208C107.075 43.4462 124.552 28.2267 141.892 12.8622ZM107.919 27.4638C112.695 23.2985 117.525 19.1865 122.407 15.128L129.572 9.29192C130.986 8.17048 132.287 6.70575 134.135 6.79729C135.234 6.89859 136.295 7.25089 137.238 7.82718C137.14 7.8666 137.054 7.92948 136.987 8.01028C119.692 23.2679 102.276 38.4264 84.7384 53.4858L82.3199 50.8767C90.739 42.9122 99.2265 35.0621 107.919 27.4638ZM81.8864 79.5306C81.8233 79.0684 81.6846 78.6199 81.4757 78.2032C79.998 75.4964 77.9409 73.1516 75.4523 71.3372L74.745 71.0397C76.0683 66.348 77.3688 61.6562 78.6922 56.9645C84.3961 63.0676 90.0925 69.2164 95.7813 75.411C91.1572 76.83 86.5256 78.2032 81.8864 79.5306ZM148.783 27.0976L144.699 30.851C139.953 35.2452 135.238 39.6852 130.553 44.1709C120.924 53.3255 111.365 62.5946 101.691 71.6347C100.755 70.6277 99.7969 69.6207 98.8614 68.5908L149.786 21.5591C150.401 22.3221 150.838 23.2137 151.064 24.1681C151.178 25.3582 149.65 26.3195 148.783 27.0976Z" fill="#38A987"/>
</svg>
							</span>
						</div>
						<div class="col-12 col-md-9 mb-3 mb-md-0">
							<span class="search-without-result__no-results"><i class="fa fa-times mr-3 font-color-promo" aria-hidden="true"></i>Nous n’avons trouvé aucun résultat pour votre recherche...</span>
							<h4 class="mt-5 mb-3 global-search-overlay__title">Ce que cherchent nos clients</h4>
							<div class="d-inline-flex d-md-flex mb-3 algolia-inspiration w-100">
								<a href="/search?query=Savon" class="global-search-overlay__link rounded-xl label-zoom px-3">Savon<span class="pl-2 d-flex align-items-center"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M10.9498 4.17588C10.5726 3.91666 10.1479 3.73623 9.70066 3.6452C9.25339 3.55417 8.79259 3.55439 8.3454 3.64583C8.07232 3.71832 7.83863 3.89668 7.69511 4.14213C7.5516 4.38758 7.50987 4.68028 7.57899 4.95662C7.64906 5.21223 7.81031 5.43265 8.03172 5.57546C8.25312 5.71828 8.51904 5.77339 8.77828 5.73019C8.92204 5.71236 9.06741 5.71236 9.21116 5.73019C9.31926 5.76552 9.42381 5.81109 9.52341 5.86629C9.63697 5.9222 9.74611 5.98686 9.84984 6.05968C9.94659 6.14062 10.0368 6.22924 10.1195 6.3247C10.1763 6.41782 10.233 6.5181 10.2827 6.61838C10.3242 6.72486 10.3551 6.83523 10.375 6.94786C10.4471 7.22343 10.6227 7.4598 10.8646 7.60684C11.1092 7.74948 11.3984 7.79301 11.6736 7.7286C11.9447 7.65554 12.1765 7.47809 12.3194 7.23437C12.4514 6.98429 12.494 6.69591 12.44 6.41782C12.3544 5.96196 12.1782 5.52833 11.922 5.14303C11.6659 4.75773 11.3352 4.42874 10.9498 4.17588Z" fill="currentColor"/>
  <path d="M23.5035 15.2638C21.0576 13.9125 18.5999 12.5969 16.1303 11.3172C16.5932 10.1638 16.7891 8.91908 16.703 7.67776C16.6169 6.43645 16.2511 5.23136 15.6336 4.1544C14.629 2.5272 13.1249 1.2762 11.3504 0.592106C9.57595 -0.0919846 7.62854 -0.171635 5.80506 0.3653C4.16033 0.891567 2.70141 1.88654 1.60399 3.2304C0.52215 4.72471 -0.0415287 6.5373 0.000208478 8.38758C0.0241508 10.1639 0.60201 11.8875 1.6517 13.3134C2.7014 14.7394 4.16956 15.7953 5.84764 16.3311C7.32131 16.8389 8.90894 16.9063 10.4196 16.5251C11.9303 16.1439 13.2998 15.3303 14.3633 14.1823C14.446 14.3683 14.592 14.5182 14.7749 14.6049L19.4585 16.8325L20.5656 17.3625C20.9625 17.5772 21.3726 17.7662 21.7932 17.9284C22.0343 18.0022 22.2908 18.0078 22.5348 17.9446C22.7788 17.8815 23.0009 17.752 23.177 17.5702C23.3049 17.4428 23.4148 17.2982 23.5035 17.1405C23.5035 17.1405 23.5389 17.0832 23.5531 17.0474C23.6939 16.944 23.8076 16.8076 23.8844 16.6499C23.9612 16.4922 23.9987 16.318 23.9938 16.1424C23.9889 15.9668 23.9417 15.795 23.8563 15.6419C23.7709 15.4888 23.6498 15.3591 23.5035 15.2638ZM7.50819 14.5476C6.25165 14.3526 5.08134 13.7836 4.14708 12.9133C3.21283 12.043 2.55714 10.9112 2.26396 9.66255C1.92469 8.04778 2.1982 6.3633 3.03037 4.9423C3.81866 3.75952 4.98247 2.88292 6.33019 2.45682C7.54723 2.07696 8.8473 2.06153 10.0729 2.41242C11.2984 2.76331 12.3968 3.46545 13.235 4.43374C14.0372 5.42432 14.5122 6.64336 14.5937 7.92009C14.6751 9.19683 14.3588 10.4673 13.6892 11.5535C13.0943 12.6398 12.1783 13.5115 11.0689 14.047C9.95957 14.5825 8.71226 14.7549 7.5011 14.5404L7.50819 14.5476Z" fill="currentColor"/>
  <path d="M9.20424 11.3315C8.95915 11.1976 8.67462 11.1569 8.40235 11.2169H8.34557C8.1945 11.2307 8.04248 11.2307 7.89141 11.2169C7.7539 11.189 7.61869 11.1507 7.48691 11.1023L7.29531 11.002C7.17355 10.9262 7.03773 10.8763 6.89617 10.8553C6.75462 10.8344 6.61032 10.8429 6.47213 10.8802C6.19964 10.9499 5.96517 11.1248 5.81926 11.3673C5.67513 11.6132 5.63184 11.9062 5.69862 12.1838C5.77466 12.4575 5.94931 12.6925 6.18827 12.8428C7.00729 13.3287 7.97613 13.4895 8.90619 13.2941C9.17823 13.2193 9.41173 13.0425 9.55906 12.7998C9.70151 12.5532 9.74228 12.26 9.6726 11.9833C9.60678 11.7126 9.43869 11.4787 9.20424 11.3315Z" fill="currentColor"/>
</svg>
</span></a>
					 
							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
	</div>



		</div>
	</div>
</div>

@stop
 



 

 
<script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.5.1/dist/algoliasearch-lite.umd.js" integrity="sha256-EXPXz4W6pQgfYY3yTpnDa3OH8/EPn16ciVsPQ/ypsjk=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.8.3/dist/instantsearch.production.min.js" integrity="sha256-LAGhRRdtVoD6RLo2qDQsU2mp+XVSciKRC8XPOBWmofM=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>



  <script defer src="{ asset('js/search.js') }"></script>
 
    </body>
</html> 