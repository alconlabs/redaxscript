<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the article model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Article extends ContentAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'articles';

	/**
	 * get the article by alias
	 *
	 * @since 4.0.0
	 *
	 * @param string $articleAlias alias of the article
	 *
	 * @return object|null
	 */

	public function getByAlias(string $articleAlias = null) : ?object
	{
		return $this->query()->where('alias', $articleAlias)->findOne() ? : null;
	}

	/**
	 * count the articles by category and language
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param string $language
	 *
	 * @return int|null
	 */

	public function countByCategoryAndLanguage(int $categoryId = null, string $language = null) : ?int
	{
		return $this
			->query()
			->where(
			[
				'category' => $categoryId,
				'status' => 1
			])
			->whereLanguageIs($language)
			->count() ? : null;
	}

	/**
	 * get the articles by category and language and order and step
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param string $language
	 * @param string $orderColumn
	 * @param int $limitStep
	 *
	 * @return object|null
	 */

	public function getByCategoryAndLanguageAndOrderAndStep(int $categoryId = null, string $language = null, string $orderColumn = null, int $limitStep = null) : ?object
	{
		return $this
			->query()
			->where(
			[
				'category' => $categoryId,
				'status' => 1
			])
			->whereLanguageIs($language)
			->orderBySetting($orderColumn)
			->limitBySetting($limitStep)
			->findMany() ? : null;
	}

	/**
	 * get the article route by id
	 *
	 * @since 3.3.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return string|null
	 */

	public function getRouteById(int $articleId = null) : ?string
	{
		$route = null;
		$articleArray = $this
			->query()
			->tableAlias('a')
			->leftJoinPrefix('categories', 'a.category = c.id', 'c')
			->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
			->select('p.alias', 'parent_alias')
			->select('c.alias', 'category_alias')
			->select('a.alias', 'article_alias')
			->where('a.id', $articleId)
			->findArray();

		/* handle route */

		if (is_array($articleArray[0]))
		{
			$route = implode('/', array_filter($articleArray[0]));
		}
		return $route;
	}
}
