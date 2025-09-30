document.addEventListener('DOMContentLoaded', () => {
  const accordionContainers = document.querySelectorAll('[data-accordion]');

  accordionContainers.forEach((container) => {
    const items = Array.from(container.querySelectorAll('[data-accordion-item]'));

    const setPanelState = (entry, expand) => {
      const panel = entry.querySelector('[data-accordion-content]');
      if (!panel) return;

      if (expand) {
        panel.style.maxHeight = '0px';
        requestAnimationFrame(() => {
          panel.style.maxHeight = panel.scrollHeight + 'px';
        });
      } else {
        panel.style.maxHeight = panel.scrollHeight + 'px';
        requestAnimationFrame(() => {
          panel.style.maxHeight = '0px';
        });
      }
    };

    const closeAll = () => {
      items.forEach((entry) => {
        if (!entry.classList.contains('is-open')) {
          const panel = entry.querySelector('[data-accordion-content]');
          if (panel) {
            panel.style.maxHeight = '0px';
          }
          return;
        }

        entry.classList.remove('is-open');
        setPanelState(entry, false);
      });
    };

    items.forEach((item) => {
      const trigger = item.querySelector('[data-accordion-trigger]');
      if (!trigger) return;

      trigger.addEventListener('click', () => {
        const isCurrentlyOpen = item.classList.contains('is-open');
        if (isCurrentlyOpen) {
          closeAll();
          return;
        }

        closeAll();
        item.classList.add('is-open');
        setPanelState(item, true);
      });
    });

    items
      .filter((entry) => entry.classList.contains('is-open'))
      .forEach((entry) => {
        const panel = entry.querySelector('[data-accordion-content]');
        if (panel) {
          panel.style.maxHeight = panel.scrollHeight + 'px';
        }
      });

    window.addEventListener('resize', () => {
      items
        .filter((entry) => entry.classList.contains('is-open'))
        .forEach((entry) => setPanelState(entry, true));
    });
  });

  injectProductStructuredData();

  function injectProductStructuredData() {
    const structuredDataElement = document.getElementById('product-structured-data');
    if (!structuredDataElement) return;

    const origin = window.location.origin && window.location.origin !== 'null'
      ? window.location.origin
      : 'https://trygo.io';
    const pageUrl = `${origin}${window.location.pathname}${window.location.search}`;

    const heroTitle = (document.querySelector('.hero h1')?.textContent || document.title).trim();
    const heroLead = (document.querySelector('.hero-lead')?.textContent || '').trim();

    const featureList = Array.from(document.querySelectorAll('.feature-item')).map((item) => {
      const title = item.querySelector('h3')?.textContent?.trim() || '';
      const description = item.querySelector('p')?.textContent?.trim() || '';
      return description ? `${title}: ${description}` : title;
    }).filter(Boolean);

    const audienceNodes = Array.from(document.querySelectorAll('.audience-card')).map((card) => ({
      '@type': 'Audience',
      audienceType: card.querySelector('h3')?.textContent?.trim() || '',
      description: card.querySelector('p')?.textContent?.trim() || '',
    })).filter((entry) => entry.audienceType);

    const pricingCards = Array.from(document.querySelectorAll('.pricing-card'));
    const offers = pricingCards.map((card, index) => {
      const name = card.querySelector('header h3')?.textContent?.trim() || `Plan ${index + 1}`;
      const priceText = card.querySelector('.price')?.textContent?.trim() || '';
      const planDescription = card.querySelector('.plan-text')?.textContent?.trim() || '';
      const highlights = Array.from(card.querySelectorAll('.plan-points li')).map((li) => li.textContent.trim()).filter(Boolean);
      const toolsTextRaw = card.querySelector('.plan-tools')?.textContent || '';
      const toolsText = toolsTextRaw.replace(/Tools:\s*/i, '').trim();

      const numericMatch = priceText.replace(',', '.').match(/([0-9]+(?:\.[0-9]+)?)/);
      const priceValue = numericMatch ? Number(numericMatch[1]) : 0;
      const priceCurrency = priceText.includes('$') ? 'USD' : 'USD';

      const additionalProperty = [];
      if (highlights.length) {
        additionalProperty.push({
          '@type': 'PropertyValue',
          name: 'Highlights',
          value: highlights.join(' | '),
        });
      }
      if (toolsText) {
        additionalProperty.push({
          '@type': 'PropertyValue',
          name: 'Tools',
          value: toolsText,
        });
      }

      const offer = {
        '@type': 'Offer',
        name,
        price: priceValue,
        priceCurrency,
        availability: 'https://schema.org/InStock',
        url: `${pageUrl}#pricing`,
        description: planDescription,
        itemOffered: {
          '@id': `${pageUrl}#product`,
        },
      };

      if (additionalProperty.length) {
        offer.additionalProperty = additionalProperty;
      }

      if (priceText.toLowerCase().includes('week')) {
        offer.validFor = 'P1W';
      }

      return offer;
    });

    const faqEntities = Array.from(document.querySelectorAll('[data-accordion-item]')).map((item) => {
      const question = item.querySelector('[data-accordion-trigger] span')?.textContent?.trim() || '';
      const answer = item.querySelector('[data-accordion-content]')?.textContent?.trim() || '';
      return question && answer
        ? {
            '@type': 'Question',
            name: question,
            acceptedAnswer: {
              '@type': 'Answer',
              text: answer,
            },
          }
        : null;
    }).filter(Boolean);

    const graph = [
      {
        '@type': 'Organization',
        '@id': `${origin}/#organization`,
        name: 'TRYGO',
        url: origin,
      },
      {
        '@type': 'WebSite',
        '@id': `${origin}/#website`,
        name: 'TRYGO',
        url: origin,
        publisher: {
          '@id': `${origin}/#organization`,
        },
      },
      {
        '@type': 'WebPage',
        '@id': `${pageUrl}#webpage`,
        url: pageUrl,
        name: heroTitle,
        description: heroLead,
        isPartOf: {
          '@id': `${origin}/#website`,
        },
        mainEntity: {
          '@id': `${pageUrl}#product`,
        },
      },
      {
        '@type': 'Product',
        '@id': `${pageUrl}#product`,
        name: 'TRYGO',
        description: heroLead,
        brand: {
          '@id': `${origin}/#organization`,
        },
        audience: audienceNodes,
        featureList,
        offers,
      },
    ];

    if (faqEntities.length) {
      graph.push({
        '@type': 'FAQPage',
        '@id': `${pageUrl}#faq`,
        url: `${pageUrl}#faq`,
        mainEntity: faqEntities,
      });
    }

    const structuredData = {
      '@context': 'https://schema.org',
      '@graph': graph,
    };

    structuredDataElement.textContent = JSON.stringify(structuredData, null, 2);
  }
});
