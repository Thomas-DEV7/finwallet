# 1. Single Responsibility Principle (SRP)
Cada classe ou função deve ter uma única responsabilidade.

Exemplo no projeto:
Controllers: Cada controller (como o WalletController) é responsável por uma funcionalidade específica. Ele apenas gerencia operações relacionadas ao recurso de carteiras, como depósitos, transferências e reversões.
Modelos: Cada modelo (User, Transaction, ReversalRequest) é focado apenas na lógica de dados e suas relações.
Views: As views são responsáveis exclusivamente pela exibição, sem lógica de negócio.

# 2. Open/Closed Principle (OCP)
O código deve estar aberto para extensão, mas fechado para modificação.

Exemplo no projeto:
O uso de polimorfismo em transações e a organização do tipo de transação (deposit, transfer, refund) permitem adicionar novos tipos sem modificar os métodos existentes. Por exemplo, para adicionar um novo tipo de transação, podemos criar novas entradas no banco ou lógica, sem alterar métodos como transfer ou reversalRequests.

# 3. Liskov Substitution Principle (LSP)
Objetos de uma classe base devem poder ser substituídos por objetos de suas subclasses sem alterar o comportamento do programa.

Exemplo no projeto:
No relacionamento entre as classes Eloquent:
Por exemplo, o modelo ReversalRequest utiliza os relacionamentos user e transaction. Esses relacionamentos são abstraídos e podem ser estendidos ou reutilizados sem quebrar o sistema, pois seguem a lógica padrão do Eloquent.

# 4. Interface Segregation Principle (ISP)
As classes não devem ser forçadas a depender de métodos que não utilizam.

Exemplo no projeto:
O Laravel utiliza Service Providers e interfaces desacopladas que seguimos no código. Por exemplo:
Relatórios ou logs no info() funcionam de forma independente e não impactam as operações principais.
Ao filtrar transações no método reversalRequests, usamos relacionamentos (with) específicos sem carregar dados desnecessários.

# 5. Dependency Inversion Principle (DIP)
As classes devem depender de abstrações, e não de implementações concretas.

Exemplo no projeto:
O uso de injeção de dependência no Laravel (como o uso de Request no método storeReversalRequest) segue esse princípio. O Laravel automaticamente resolve e injeta as dependências necessárias.
Eloquent Models: Os métodos em ReversalRequest e Transaction interagem com abstrações do Eloquent ORM, permitindo flexibilidade para alterar a implementação sem quebrar a lógica principal.


## Resumo Prático
```
Single Responsibility (SRP): Separação clara entre Controllers, Models e Views.
Open/Closed (OCP): Extensibilidade em tipos de transação e validações sem modificar a lógica existente.
Liskov Substitution (LSP): Uso de relacionamentos do Eloquent que podem ser substituídos ou estendidos.
Interface Segregation (ISP): Filtragem e carregamento apenas dos dados necessários nas consultas.
Dependency Inversion (DIP): Injeção de dependências no Laravel (como Request) e uso de abstrações (Eloquent ORM).
Se precisar de mais detalhes sobre um exemplo específico, posso aprofundar! 
```