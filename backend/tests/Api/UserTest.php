<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    public function testNoParameterRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['currentPage' => 1]);
    }

    public function testIsActiveRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/users?isActive=1&page=1&pageSize=1');
        //todo: Test data may change, this assert approach should be more accurate in real projects
        $expectResult = '{"currentPage":1,"lastPage":134,"pageSize":1,"previousPage":1,"nextPage":2,"toPaginate":true,"numResults":134,"results":[{"id":195,"username":"test_195_user","email":"test_195@tnc.com","createdAt":"2009-01-09T07:34:50+00:00","updatedAt":"2022-06-24T11:58:13+00:00","isMember":false,"isActive":true,"userType":2,"lastLoginAt":"2022-03-30 05:27:00"}]}';

        $this->assertResponseIsSuccessful();
        $this->assertJsonEquals($expectResult);
    }

    public function testIsMemberRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/users?isMember=1&page=1&pageSize=1');
        $expectResult = '{"currentPage":1,"lastPage":38,"pageSize":1,"previousPage":1,"nextPage":2,"toPaginate":true,"numResults":38,"results":[{"id":328,"username":"test_328_user","email":"test_328@tnc.com","createdAt":"2009-02-05T15:17:35+00:00","updatedAt":"2022-06-24T11:58:13+00:00","isMember":true,"isActive":true,"userType":1,"lastLoginAt":"2020-10-20 20:07:33"}]}';

        $this->assertResponseIsSuccessful();
        $this->assertJsonEquals($expectResult);
    }

    public function testLastLoginFromRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/users?lastLoginFrom=2014-10-10%2000:00:00&page=1&pageSize=1');
        $expectResult = '{"currentPage":1,"lastPage":233,"pageSize":1,"previousPage":1,"nextPage":2,"toPaginate":true,"numResults":233,"results":[{"id":195,"username":"test_195_user","email":"test_195@tnc.com","createdAt":"2009-01-09T07:34:50+00:00","updatedAt":"2022-06-24T11:58:13+00:00","isMember":false,"isActive":true,"userType":2,"lastLoginAt":"2022-03-30 05:27:00"}]}';

        $this->assertResponseIsSuccessful();
        $this->assertJsonEquals($expectResult);
    }

    public function testLastLoginToRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/users?lastLoginTo=2014-10-10%2000:00:00&page=1&pageSize=1');
        $expectResult = '{"currentPage":1,"lastPage":232,"pageSize":1,"previousPage":1,"nextPage":2,"toPaginate":true,"numResults":232,"results":[{"id":222,"username":"test_222_user","email":"test_222@tnc.com","createdAt":"2009-01-20T23:20:26+00:00","updatedAt":"2022-06-24T11:58:13+00:00","isMember":false,"isActive":false,"userType":1,"lastLoginAt":"2009-01-20 23:22:33"}]}';

        $this->assertResponseIsSuccessful();
        $this->assertJsonEquals($expectResult);
    }

    public function testUserTypeRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/users?userTypes=2,3&page=1&pageSize=1');
        $expectResult = '{"currentPage":1,"lastPage":33,"pageSize":1,"previousPage":1,"nextPage":2,"toPaginate":true,"numResults":33,"results":[{"id":195,"username":"test_195_user","email":"test_195@tnc.com","createdAt":"2009-01-09T07:34:50+00:00","updatedAt":"2022-06-24T11:58:13+00:00","isMember":false,"isActive":true,"userType":2,"lastLoginAt":"2022-03-30 05:27:00"}]}';

        $this->assertResponseIsSuccessful();
        $this->assertJsonEquals($expectResult);
    }

    public function testMultiParameterRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/users?isActive=1&isMember=1&userTypes=1&page=1&pageSize=1');
        $expectResult = '{"currentPage":1,"lastPage":9,"pageSize":1,"previousPage":1,"nextPage":2,"toPaginate":true,"numResults":9,"results":[{"id":328,"username":"test_328_user","email":"test_328@tnc.com","createdAt":"2009-02-05T15:17:35+00:00","updatedAt":"2022-06-24T11:58:13+00:00","isMember":true,"isActive":true,"userType":1,"lastLoginAt":"2020-10-20 20:07:33"}]}';

        $this->assertResponseIsSuccessful();
        $this->assertJsonEquals($expectResult);
    }
}
